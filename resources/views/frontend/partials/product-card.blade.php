<article class="col-md-2 col-6 d-flex">
    <div class="book-card h-100 p-0">
        <div class="book-image d-flex justify-content-center align-items-center">
            @if ($product->photo)
                @if (isset($product->is_api_product) && $product->is_api_product)
                    <img src="{{ asset($product->photo) }}"
                        alt="{{ languageText($product->name, $product->nama) }}"
                        class="img-fluid" loading="lazy" />
                @else
                    <img src="{{ asset($product->photo) }}"
                        alt="{{ languageText($product->name, $product->nama) }}"
                        class="img-fluid" loading="lazy" />
                @endif
            @else
                <img src="{{ asset('frontend/assets/img/default-product.png') }}"
                    alt="{{ languageText($product->name, $product->nama) }}"
                    class="img-fluid" loading="lazy" />
            @endif
        </div>
        <h4>{{ languageText($product->name, $product->nama) }}</h4>
        <div class="d-flex justify-content-evenly button-wrap">
            @php
                $buyNowText = languageText('Buy Now', 'Belanja Sekarang');
                $downloadSampleText = languageText('View Book Sample', 'Lihat Sampel Buku');
            @endphp
            @if (isset($product->is_api_product) && $product->is_api_product)
                <a href="{{ $product->link ?? $product->url }}"
                    target="_blank"
                    class="btn btn-outline-primary"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    data-bs-title="{{ $buyNowText }}"
                    title="{{ $buyNowText }}"><i
                        class="m-0 bi bi-cart"></i></a>
            @else
                <a href="{{ $product->link ?? url('product/' . $product->url) }}"
                    class="btn btn-outline-primary"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    data-bs-title="{{ $buyNowText }}"
                    title="{{ $buyNowText }}"><i
                        class="m-0 bi bi-cart"></i></a>
            @endif
            @if ($product->type_sample == 'internal')
                @if ($product->sample_product_id && isset($product->sampleProduct) && $product->sampleProduct)
                    <a href="{{ asset($product->sampleProduct->file) }}"
                        class="btn btn-outline-primary"
                        target="_blank" 
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-title="{{ $downloadSampleText }}"
                        title="{{ $downloadSampleText }}"><i
                            class="m-0 bi bi-download"></i></a>
                @endif
            @else
                @if ($product->type_sample == 'external')
                    <a href="{{ asset($product->link_sample) }}" class="btn btn-outline-primary"
                        target="_blank" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="{{ languageText('View Book Sample', 'Lihat Sampel Buku') }}"
                        title="{{ languageText('View Book Sample', 'Lihat Sampel Buku') }}"><i
                            class="m-0 bi bi-download"></i></a>
                @endif
            @endif
            
        </div>
    </div>
</article>

