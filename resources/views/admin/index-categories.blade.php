@extends('core::admin.master')

@section('title', __('Categories'))

@section('content')

<item-list url-base="/api/forum/categories" fields="id,position,name,color" table="forum_categories" title="categories" :publishable="false" :searchable="['name,color']" :sorting="['position']">
    <template slot="add-button" v-if="$can('create forum_categories')">
        @include('core::admin._button-create', ['url' => route('admin::create-forum-category')])
    </template>

    <template slot="columns" slot-scope="{ sortArray }">
        <item-list-column-header name="checkbox" v-if="$can('update forum_categories')||$can('delete forum_categories')"></item-list-column-header>
        <item-list-column-header name="edit" v-if="$can('update forum_categories')"></item-list-column-header>
        <item-list-column-header name="position" sortable :sort-array="sortArray" :label="$t('Position')"></item-list-column-header>
        <item-list-column-header name="name_translated" sortable :sort-array="sortArray" :label="$t('Name')"></item-list-column-header>
        <item-list-column-header name="color" sortable :sort-array="sortArray" :label="$t('Color')"></item-list-column-header>
    </template>

    <template slot="table-row" slot-scope="{ model, checkedModels, loading }">
        <td class="checkbox" v-if="$can('update forum_categories')||$can('delete forum_categories')">
            <item-list-checkbox :model="model" :checked-models-prop="checkedModels" :loading="loading"></item-list-checkbox>
        </td>
        <td v-if="$can('update forum_categories')">
            <item-list-edit-button :url="'/admin/categories/'+model.id+'/edit'"></item-list-edit-button>
        </td>
        <td>
            <item-list-position-input :model="model"></item-list-position-input>
        </td>
        <td>@{{ model.name_translated }}</td>
        <td>
            <span :style="'color:'+model.color">●</span>
            <span class="text-muted">@{{ model.color }}</span>
        </td>
    </template>
</item-list>

@endsection
