<?php

namespace TypiCMS\Modules\Forum\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        if (Gate::denies('read forum_categories') && Gate::denies('read forum_discussions')) {
            return;
        }
        $view->sidebar->group(__('Forum'), function (SidebarGroup $group) {
            $group->id = 'forum';
            $group->weight = 30;
            if (Gate::allows('read forum_categories')) {
                $group->addItem(__('Categories'), function (SidebarItem $item) {
                    $item->id = 'forum-categories';
                    $item->icon = config('typicms.forum.categories.sidebar.icon', 'icon fa fa-fw fa-list');
                    $item->weight = config('typicms.forum.categories.sidebar.weight');
                    $item->route('admin::index-forum-categories');
                    $item->append('admin::create-forum-category');
                });
            }
            if (Gate::allows('read forum_discussions')) {
                $group->addItem(__('Discussions'), function (SidebarItem $item) {
                    $item->id = 'forum-discussions';
                    $item->icon = config('typicms.forum.discussions.sidebar.icon', 'icon fa fa-fw fa-comment-o');
                    $item->weight = config('typicms.forum.discussions.sidebar.weight');
                    $item->route('admin::index-forum-discussions');
                });
            }
        });
    }
}
