@extends('core::admin.master')

@section('title', __('Discussions'))

@section('content')

<item-list
    url-base="/api/forum/discussions"
    fields="id,forum_category_id,user_id,title,slug,sticky,views,answered,last_reply_at"
    table="forum_discussions"
    title="discussions"
    :publishable="false"
    :searchable="['title']"
    :sorting="['-last_reply_at']"
>
    <template slot="columns" slot-scope="{ sortArray }">
        <item-list-column-header
            name="checkbox"
            v-if="$can('update forum_discussions')||$can('delete forum_discussions')"
        ></item-list-column-header>
        <item-list-column-header name="show"></item-list-column-header>
        <item-list-column-header
            name="last_reply_at"
            sortable
            :sort-array="sortArray"
            :label="$t('Last reply')"
        ></item-list-column-header>
        <item-list-column-header
            name="title"
            sortable
            :sort-array="sortArray"
            :label="$t('Title')"
        ></item-list-column-header>
        <item-list-column-header
            name="views"
            sortable
            :sort-array="sortArray"
            :label="$t('Views')"
        ></item-list-column-header>
        <item-list-column-header
            name="forum_category_id"
            sortable
            :sort-array="sortArray"
            :label="$t('Category')"
        ></item-list-column-header>
    </template>

    <template slot="table-row" slot-scope="{ model, checkedModels, loading }">
        <td class="checkbox" v-if="$can('update forum_discussions')||$can('delete forum_discussions')">
            <item-list-checkbox
                :model="model"
                :checked-models-prop="checkedModels"
                :loading="loading"
            ></item-list-checkbox>
        </td>
        <td>
            <item-list-show-button :url="'/admin/discussions/'+model.id"></item-list-show-button>
        </td>
        <td>@{{ model.last_reply_at | datetime }}</td>
        <td>@{{ model.title }}</td>
        <td><span class="badge bg-secondary">@{{ model.views }}</span></td>
        <td>@{{ model.forum_category_id }}</td>
    </template>
</item-list>

@endsection
