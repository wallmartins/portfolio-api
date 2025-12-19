<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use App\Controller\Admin\AboutController as AdminAboutController;
use App\Controller\Admin\AuthController;
use App\Controller\Admin\ExperiencesController as AdminExperiencesController;
use App\Controller\Admin\PostController as AdminBlogController;
use App\Controller\Admin\ProjectsController as AdminProjectsController;
use App\Controller\Admin\SocialController as AdminSocialController;
use App\Controller\Admin\TechsController as AdminTechsController;
use App\Controller\Portfolio\AboutController;
use App\Controller\Portfolio\ChatController;
use App\Controller\Portfolio\ExperiencesController;
use App\Controller\Portfolio\PostController;
use App\Controller\Portfolio\ProjectsController;
use App\Controller\Portfolio\SocialController;
use App\Controller\Portfolio\TechsController;
use App\Middleware\AuthMiddleware;
use Hyperf\HttpServer\Router\Router;
use Hyperf\Session\Middleware\SessionMiddleware;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::addGroup('/portfolio/', function () {
    Router::get('about', [AboutController::class, 'index', ['as' => 'portfolio.about']]);
    Router::get('blog', [PostController::class, 'index', ['as' => 'portfolio.index.blog']]);
    Router::get('blog/{slug}', [PostController::class, 'show', ['as' => 'portfolio.show.blog']]);
    Router::get('experiences', [ExperiencesController::class, 'index', ['as' => 'portfolio.experiences']]);
    Router::get('projects', [ProjectsController::class, 'index', ['as' => 'portfolio.index.projects']]);
    Router::get('projects/{id}', [ProjectsController::class, 'show', ['as' => 'portfolio.show.projects']]);
    Router::get('social', [SocialController::class, 'index', ['as' => 'portfolio.social']]);
    Router::get('techs', [TechsController::class, 'index', ['as' => 'portfolio.techs']]);
    Router::post('chat', [ChatController::class, 'chat', ['as' => 'portfolio.chat']]);
}, ['name' => 'portfolio.']);

Router::addGroup('/auth/', function () {
    Router::get('github/redirect', [AuthController::class, 'redirect'], ['as' => 'auth.github.redirect', 'middleware' => [SessionMiddleware::class]]);
    Router::get('github/callback', [AuthController::class, 'callback'], ['as' => 'auth.github.callback', 'middleware' => [SessionMiddleware::class]]);
    Router::post('logout', [AuthController::class, 'logout'], ['as' => 'auth.logout', 'middleware' => [AuthMiddleware::class, SessionMiddleware::class]]);
    Router::put('me', [AuthController::class, 'me'], ['as' => 'auth.me', 'middleware' => [AuthMiddleware::class, SessionMiddleware::class]]);
}, ['name' => 'auth.']);

Router::addGroup('/about/', function () {
    Router::get('', [AdminAboutController::class, 'index'], ['as' => 'about.index']);
    Router::get('{id}', [AdminAboutController::class, 'show'], ['as' => 'about.show']);
    Router::post('create', [AdminAboutController::class, 'store'], ['as' => 'about.store']);
    Router::put('{id}', [AdminAboutController::class, 'update'], ['as' => 'about.update']);
    Router::delete('{id}', [AdminAboutController::class, 'destroy'], ['as' => 'about.destroy']);
}, ['middleware' => [AuthMiddleware::class], 'name' => 'about.']);

Router::addGroup('/blog/', function () {
    Router::get('', [AdminBlogController::class, 'index'], ['as' => 'blog.index']);
    Router::get('{id}', [AdminBlogController::class, 'show'], ['as' => 'blog.show']);
    Router::post('create', [AdminBlogController::class, 'store'], ['as' => 'blog.store']);
    Router::put('{id}', [AdminBlogController::class, 'update'], ['as' => 'blog.update']);
    Router::delete('{id}', [AdminBlogController::class, 'destroy'], ['as' => 'blog.destroy']);
}, ['middleware' => [AuthMiddleware::class], 'name' => 'blog.']);

Router::addGroup('/experiences/', function () {
    Router::get('', [AdminExperiencesController::class, 'index'], ['as' => 'experiences.index']);
    Router::get('{id}', [AdminExperiencesController::class, 'show'], ['as' => 'experiences.show']);
    Router::post('create', [AdminExperiencesController::class, 'store'], ['as' => 'experiences.store']);
    Router::put('{id}', [AdminExperiencesController::class, 'update'], ['as' => 'experiences.update']);
    Router::delete('{id}', [AdminExperiencesController::class, 'destroy'], ['as' => 'experiences.destroy']);
}, ['middleware' => [AuthMiddleware::class], 'name' => 'experiences.']);

Router::addGroup('/projects/', function () {
    Router::get('', [AdminProjectsController::class, 'index'], ['as' => 'projects.index']);
    Router::get('{id}', [AdminProjectsController::class, 'show'], ['as' => 'projects.show']);
    Router::post('create', [AdminProjectsController::class, 'store'], ['as' => 'projects.store']);
    Router::put('{id}', [AdminProjectsController::class, 'update'], ['as' => 'projects.update']);
    Router::delete('{id}', [AdminProjectsController::class, 'destroy'], ['as' => 'projects.destroy']);
}, ['middleware' => [AuthMiddleware::class], 'name' => 'projects.']);

Router::addGroup('/social/', function () {
    Router::get('', [AdminSocialController::class, 'index'], ['as' => 'social.index']);
    Router::post('create', [AdminSocialController::class, 'store'], ['as' => 'social.store']);
    Router::put('{id}', [AdminSocialController::class, 'update'], ['as' => 'social.update']);
    Router::delete('{id}', [AdminSocialController::class, 'destroy'], ['as' => 'social.destroy']);
}, ['middleware' => [AuthMiddleware::class], 'name' => 'social.']);

Router::addGroup('/techs/', function () {
    Router::get('', [AdminTechsController::class, 'index'], ['as' => 'techs.index']);
    Router::post('create', [AdminTechsController::class, 'store'], ['as' => 'techs.store']);
    Router::delete('{id}', [AdminTechsController::class, 'destroy'], ['as' => 'techs.destroy']);
}, ['middleware' => [AuthMiddleware::class], 'name' => 'techs.']);

Router::get('/favicon.ico', function () {
    return '';
});
