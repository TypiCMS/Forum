<?php

namespace TypiCMS\Modules\Forum\Composers;

use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
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
                    $item->icon = config('typicms.modules.forum_categories.sidebar.icon');
                    $item->weight = config('typicms.modules.forum_categories.sidebar.weight');
                    $item->route('admin::index-forum-categories');
                    $item->append('admin::create-forum-category');
                });
            }
            if (Gate::allows('read forum_discussions')) {
                $group->addItem(__('Discussions'), function (SidebarItem $item) {
                    $item->id = 'forum-discussions';
                    $item->icon = config('typicms.modules.forum_discussions.sidebar.icon');
                    $item->weight = config('typicms.modules.forum_discussions.sidebar.weight');
                    $item->route('admin::index-forum-discussions');
                });
            }
        });
    }
}
