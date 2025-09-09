<div class="container-fluid">
    <div class="container pb-5">
        <div class="row pt-5">
            <div class="col-md-12 px-0 py-5 text-center">
                <h1 class="montserrat-900 mb-0 text-green">PREGUNTAS FRECUENTES</h1>
            </div>
        </div>
        <div class="row">
            @foreach($faqs as $faq)
                <div class="col-md-12 mb-3">
                    <div class="bg-beige py-4 px-5 rounded-4">
                        <p class="montserrat-700 font-size-22 mb-2 text-uppercase">{{ $faq->title }}</p>
                        <p class="font-size-22 mb-0">{{ $faq->text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
