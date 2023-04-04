@extends('base')

@section('content')

    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">Financement participatif</h1>
                <p class="lead text-body-secondary">
                    Ici, vous trouverez une sélection des meilleures plateformes d'investissement disponibles en ligne, chacune offrant une gamme unique d'opportunités d'investissement pour les investisseurs de tous niveaux et de tous horizons.
                    Que vous soyez un investisseur débutant cherchant à faire vos premiers pas dans le monde de l'investissement, ou un investisseur expérimenté cherchant à diversifier votre portefeuille, cette liste est conçue pour vous aider à trouver les plateformes les plus adaptées à vos besoins.
                    Explorez ma sélection dès maintenant et commencez à investir pour votre avenir dès aujourd'hui !
                </p>
                <!--<p>
                    <a href="#" class="btn btn-primary my-2">Main call to action</a>
                    <a href="#" class="btn btn-secondary my-2">Secondary action</a>
                </p>-->
            </div>
        </div>
    </section>

    <div class="album py-5 bg-body-tertiary">
        <div class="container">


            <div class="accordion" id="accordionPanelsStayOpen">
            @if($categories->count())
                @foreach($categories as $category)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $category->id }}" aria-expanded="true" aria-controls="collapse{{ $category->id }}">
                                {{ $category->name }}
                            </button>
                        </h2>
                        <div id="collapse{{ $category->id }}" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                {{ $category->description }}
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                                @foreach($category->platforms as $platform)
                                    <div class="col">
                                        <div class="card shadow-sm">
                                            <img class="bd-placeholder-img card-img-top" width="100%" height="225" alt="" src="{{ $platform->image_path }}" />
                                            <div class="card-body">
                                                <p class="card-text">
                                                    <strong>{{ $platform->name }}</strong>
                                                </p>
                                                <p class="card-text">
                                                    {{ $platform->short_description }}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="btn-group">
                                                        <a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{ $platform->link }}">
                                                            {{ __('View') }}
                                                        </a>
                                                    </div>
                                                    <small class="text-body-secondary">9 mins</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <h1>{{ __('No categories') }}</h1>
            @endif
            </div>

        </div>
    </div>

@endsection
