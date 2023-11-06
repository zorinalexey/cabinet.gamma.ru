@extends('layouts.front_main')

@section('title', $content->title)

@section('content')
    <div class="content-body content-documents">
        {!! $content->content !!}
    </div>
@stop
