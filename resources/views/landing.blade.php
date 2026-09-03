@extends('layouts.landing')

@section('content')
    @include('partials.navbar')

    @include('sections.hero')
    @include('partials.countdown')
    @include('sections.about')
    @include('sections.why')
    @include('sections.timeline')
    @include('sections.competition')
    @include('sections.prize')
    @include('sections.sponsor')
    @include('sections.faq')
    @include('sections.contact')

    @include('partials.footer')
@endsection