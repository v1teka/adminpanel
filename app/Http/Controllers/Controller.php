<?php

namespace App\Http\Controllers;

use App\Article;
use App\User;
use App\Material;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function adminPanel()
    {
        if(Auth::check() && Auth::user()['level']>0)
            return view('adminpanel');
        else
        return view('403');
    }

    public function editArticle($id=NULL)
    {
        if(Auth::check()){
            if(isset($id)){
                if(Auth::user()->level>0){
                    $article = Material::find($id)->toArray();
                    $article['body']=Article::find($id)->body;
                    if($article){
                        return view('editor')->with($article);
                    }
                    else return redirect(url('/admin?status=wrong'));
                }else return view('403');
            }else return view('editor');
        }
        else return view('403');
    }

    public function insertArticle(Request $request)
    {
        if($request['parent_id']!=0){
            if($request->id){
                $article = Article::find($request->id);
                $material = Material::find($request->id);
            }
            else{
                $article = new Article;
                $material = new Material;
            }
            $material['isArticle'] = true;
            $material['title'] = $request['title'];
            $material['parent_id'] = $request['parent_id'];
            $material->save();
            $article['id'] = $material['id'];
            $article['body'] = $request['body'];
            $article['author'] = Auth::user()->name;
            $article->save();
            $status = 'ok';
        }else $status = 'wrong';
        

        return redirect(url('/articles?status='.$status));
    }

    public function listArticles(){
        $articles = Material::where('isArticle', true)->get();
        return view('materials', ['materials' => $articles]);
    }

    public function showArticle($id){
        $article = Article::find($id);
        $material = Material::find($id);
        $article['title'] = $material['title'];
        $parent_id = $material['parent_id'];
        $article['p_id'] = $parent_id;
        if($parent_id == 0)
            $article['p_title'] = 'В корень';
        else
            $article['p_title'] = Material::find($material['parent_id'])->title;
        return view('article', $article);
    }

    public function listSections(){
        $materials = Material::all();
        return view('materials', ['materials' => $materials]);
    }

    public function showSection($id=0){
        $sections = Material::where('parent_id', $id)->get();
        return view('materials', ['materials' => $sections, 'section_id' => $id]);
    }

    public function adminSection(Request $request){
        if($request->id=='' || $request->id==0)
            $status = 'wrong';
        else{
            if($request->menu)
                $status = $this->ToggleMenuMaterial($request->id);
            else if($request->delete)
                $status = $this->DeleteSection($request->id);
            else{
                $material = Material::find($request->id);
                if($material){
                    $material->title = $request->title;
                    $material->save();
                    $status = 'ok';
                }
                else $status = 'wrong';
            }
        }

        return redirect('/admin?status='.$status);
    }

    public function insertSection(Request $request){
        if(isset($request['section_name']) && $request['section_name']!=''){
            $section = new Material;
            $section['title'] = $request['section_name'];
            $section['parent_id'] = $request['parent_id'];
            $section->save();
            $status = 'ok';
        }else  $status = 'wrong';
        return redirect('/section'.$request->parent_id.'?status='.$status);
    }

    public function adminLevel(Request $request){
        $username = $request->username;
        $newlevel = $request->newlevel;
        $status = 'wrong';
        if(Auth::user()->name != $username && Auth::user()->level > $newlevel){
            $user = User::where('name', $username)->first();
            if(Auth::user()->level > $user->level){
                $status = 'ok';
                $user->level = $newlevel;
                $user->save();
            }
        }
        return redirect('/admin?status='.$status);
    }

    private function ToggleMenu($articleID){
        $article = Article::find($articleID);
        if($article){
            $article->isInMenu = !$article->isInMenu;
            $article->save();
            return 'ok';
        }else{
            return 'wrong';
        }
    }

    private function ToggleMenumaterial($materialID){
        $material = material::find($materialID);
        if($material){
            $material->isInMenu = !$material->isInMenu;
            $material->save();
            return 'ok';
        }else{
            return 'wrong';
        }
    }

    private function DeleteArticle($articleID){
        $article = Article::find($articleID);
        $material = Material::find($articleID);
        if($article){
            $article->delete();
            $material->delete();
            return 'ok';
        }else{
            return 'wrong';
        }
    }

    public function DeleteSection($sectionID){
        $section = Material::find($sectionID);
        if($section){
            $articleIDs = Material::where('parent_id', $sectionID)->pluck('id');
            foreach ($articleIDs as $articleID){
                $this->DeleteArticle($articleID);
            }
            $section->delete();
        }else return 'wrong';
        return 'ok';
    }
    
    public function adminArticle(Request $request){
        if($request->menu){
            $status = $this->ToggleMenu($request->articleID);
            return redirect('/admin?status='.$status);
        }else{
            if($request->edit){
                return redirect(url('/admin/article'.$request->articleID));
            }else{
                if($request->delete)
                    $status = $this->DeleteArticle($request->articleID);
                    return redirect('/admin?status='.$status);
            }
        }
    }

    public function generateJSON(){
        $materials = material::all()->pluck('title');;
        $articles = Article::all('title', 'body', 'author', 'material_id', 'isInMenu');
        $result = array("materials" => $materials, "articles" => $articles);
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
