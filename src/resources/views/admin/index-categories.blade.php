@extends('core::admin.master')

@section('title', __('Categories'))

@section('content')

<item-list
    url-base="/api/forum/categories"
    locale="{{ config('typicms.content_locale') }}"
    fields="id,position,name,color"
    table="forum_categories"
    title="categories"
    :publishable="false"
    :searchable="['name,color']"
    :sorting="['position']">

    <template slot="add-button">
        @can ('create-forum-category')
        <a class="btn btn-primary btn-sm header-btn-add mr-2" href="{{ route('admin::create-forum-category') }}">
            <span class="fa fa-plus text-white-50"></span> @lang('Add')
        </a>
        @endcan
    </template>

    <template slot="columns" slot-scope="{ sortArray }">
        <item-list-column-header name="checkbox"></item-list-column-header>
        @can ('delete-forum-category')
        <item-list-column-header name="edit"></item-list-column-header>
        @endcan
        <item-list-column-header name="position" sortable :sort-array="sortArray" :label="$t('Position')"></item-list-column-header>
        <item-list-column-header name="name" sortable :sort-array="sortArray" :label="$t('Name')"></item-list-column-header>
        <item-list-column-header name="color" sortable :sort-array="sortArray" :label="$t('Color')"></item-list-column-header>
    </template>

    <template slot="table-row" slot-scope="{ model, checkedModels, loading }">
        <td class="checkbox"><item-list-checkbox :model="model" :checked-models-prop="checkedModels" :loading="loading"></item-list-checkbox></td>
        @can ('delete-forum-category')
        <td>@include('core::admin._button-edit', ['module' => 'categories'])</td>
        @endcan
        <td><item-list-position-input :model="model"></item-list-position-input></td>
        <td>@{{ model.name }}</td>
        <td>
            <span class="fa fa-circle" :style="'color:'+model.color"></span>
            <span class="text-muted">@{{ model.color }}</span>
        </td>
    </template>

</item-list>

@endsection
