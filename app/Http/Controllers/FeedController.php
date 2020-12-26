<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\User;
use App\Models\UserRelation;
use Image;

class FeedController extends Controller
{
    private $loggedUser;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function _postListToObject($postList, $loggedUser)
    {
        foreach ($postList as $postKey => $postItem) {
            // Verifica se o post pertence ao usuário logado
            ($postItem['id_user'] == $loggedUser) ?
                $postList[$postKey]['mine'] = true :
                $postList[$postKey]['mine'] = false;

            // Preencher informações do usuário
            $userInfo = User::find($postItem['id_user']);
            $userInfo['avatar'] = url('media/avatars/' . $userInfo['avatar']);
            $userInfo['cover'] = url('media/covers/' . $userInfo['cover']);
            $postList[$postKey]['user'] = $userInfo;

            // Preencher informações de likes
            $likes = PostLike::where('id_post', $postItem['id'])->count();
            $postList[$postKey]['likeCount'] = $likes;

            $isLiked = PostLike::where('id_post', $postItem['id'])
                ->where('id_user', $loggedUser)->count();
            $postList[$postKey]['liked'] = ($isLiked > 0) ? true : false;

            // Preencher informações de comments
            $comments = PostComment::where('id_post', $postItem['id'])->get();
            foreach ($comments as $commentKey => $comment) {
                $user = User::find($comment['id_user']);
                $user['avatar'] = url('media/avatars/' . $user['avatar']);
                $user['cover'] = url('media/covers/' . $user['cover']);
                $comments[$commentKey]['user'] = $user;
            }

            $postList[$postKey]['comments'] = $comments;
        }
        return $postList;
    }

    public function create(Request $request)
    {
        $array = ['error' => ''];

        $data = $request->only(['type', 'body', 'photo']);

        $validator = Validator(
            $data,
            [
                'type' => ['string', 'required', Rule::in(['text', 'photo'])],
                'body' => ['string', 'min:2'],
                'photo' => ['image', 'mimes:jpeg,jpg,png']
            ]
        );

        if ($validator->fails()) {
            $array['error'] = $validator->errors();
            return $this->jsonResponse($array, 400);
        }

        $type = $request->input(['type']);
        $body = $request->input(['body']);
        $photo = $request->file(['photo']);

        switch ($type) {
            case 'text':
                if (!$body) {
                    $array['error'] = 'Type text selecionado, mas foi enviado body';
                    return $this->jsonResponse($array, 400);
                }
                break;
            case 'photo':
                if ($photo) {
                    $fileName = md5(time() . rand(0, 9999)) . '.jpg';
                    $path = public_path('/media/uploads');

                    $image = Image::make($photo->path())->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $fileName);

                    $body = $fileName;
                } else {
                    $array['error'] = 'Type photo selecionado, mas foi enviado photo';
                    return $this->jsonResponse($array, 400);
                }
                break;
        }

        $newPost = new Post();
        $newPost->id_user = $this->loggedUser['id'];
        $newPost->type = $type;
        $newPost->body = $body;
        $newPost->created_at = date('Y-m-d H:i:s');
        $newPost->save();

        return $this->jsonResponse($array);
    }

    public function read(Request $request)
    {
        $array = ['error' => ''];

        $page = intval($request->input('page'));
        $perPage = 2;

        // 1. Pegar a lista de usuários que o usuário segue incluindo ele.
        $users[] = $this->loggedUser['id'];
        $userList = UserRelation::where('user_from', $this->loggedUser['id'])->get();

        foreach ($userList as $userItem) {
            $users[] = $userItem['user_to'];
        }

        // 2. Pegar os posts ordenado pela data.
        $postList = Post::whereIn('id_user', $users)
            ->orderBy('created_at', 'desc')
            ->offset($page * $perPage)
            ->limit($perPage)
            ->get();

        $postTotal = Post::whereIn('id_user', $users)->count();
        $pageCount = ceil($postTotal / $perPage);

        // 3. Preencher as informações adicionais
        $posts = $this->_postListToObject($postList, $this->loggedUser['id']);

        $array['pageCount'] = $pageCount;
        $array['currentPage'] = $page;
        $array['posts'] = $posts;

        return $this->jsonResponse($array);
    }

    public function userFeed(Request $request, $id = false)
    {
        $array = ['error' => ''];

        if ($id == false) {
            $id = $this->loggedUser['id'];
        }

        $page = intval($request->input('page'));
        $perPage = 2;

        // 1. Pegar os posts do usuário ordenado pela data
        $postList = Post::where('id_user', $id)
            ->orderBy('created_at', 'desc')
            ->offset($page * $perPage)
            ->limit($perPage)
            ->get();

        $postTotal = Post::where('id_user', $id)->count();
        $pageCount = ceil($postTotal / $perPage);

        // 2. Preencher as informações adicionais
        $posts = $this->_postListToObject($postList, $this->loggedUser['id']);

        $array['pageCount'] = $pageCount;
        $array['currentPage'] = $page;
        $array['posts'] = $posts;

        return $this->jsonResponse($array);
    }

    public function userPhotos(Request $request, $id = false)
    {
        $array = ['error' => ''];

        if ($id == false) {
            $id = $this->loggedUser['id'];
        }

        $page = intval($request->input('page'));
        $perPage = 2;

        // 1. Pegar as fotos do usuário ordenado pela data
        $postList = Post::where('id_user', $id)->where('type', 'photo')
            ->orderBy('created_at', 'desc')
            ->offset($page * $perPage)
            ->limit($perPage)
            ->get();

        $postTotal = Post::where('id_user', $id)->where('type', 'photo')->count();
        $pageCount = ceil($postTotal / $perPage);

        // 2. Preencher as informações adicionais
        $posts = $this->_postListToObject($postList, $this->loggedUser['id']);

        foreach ($posts as $pKey => $post) {
            $posts[$pKey]['body'] = url('media/uploads/' . $posts[$pKey]['body']);
        }

        $array['pageCount'] = $pageCount;
        $array['currentPage'] = $page;
        $array['posts'] = $posts;

        return $this->jsonResponse($array);
    }
}
