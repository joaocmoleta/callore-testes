<div itemscope itemtype="http://schema.org/Product">
    @if (isset($sku))
    <meta itemprop="sku" content="{{ $sku }}" />
    @endif
    @if (isset($google_product_category))
        <meta itemprop="google_product_category" content="{{ $google_product_category }}">
    @endif
    @if (isset($brand))
        <meta itemprop="brand" content="{{ $brand }}">
    @endif
    @if (isset($name))
        <meta itemprop="name" content="{{ $name }}">
    @endif
    @if (isset($description))
        <meta itemprop="description" content="{{ $description }}">
    @endif
    @if (isset($productID))
        <meta itemprop="productID" content="{{ $productID }}">
    @endif
    @if (isset($url))
        <meta itemprop="url" content="{{ $url }}">
    @endif
    <meta itemprop="image" content="{{ $image ?? asset('/img/logo-callore-site.svg') }}">
    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <link itemprop="availability" href="http://schema.org/InStock">
        <link itemprop="itemCondition" href="http://schema.org/NewCondition">
        @if (isset($price))
            <meta itemprop="price" content="{{ $price }}">
        @endif
        <meta itemprop="priceCurrency" content="BRL">
        <meta itemprop="priceValidUntil" content="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />

        <div itemprop="shippingDetails" itemtype="https://schema.org/OfferShippingDetails" itemscope>
            <div itemprop="shippingRate" itemtype="https://schema.org/MonetaryAmount" itemscope>
                <meta itemprop="value" content="0" />
                <meta itemprop="currency" content="BRL" />
            </div>
            <div itemprop="shippingDestination" itemtype="https://schema.org/DefinedRegion" itemscope>
                <meta itemprop="addressCountry" content="BR" />
            </div>
        </div>
    </div>
    <meta property="product:category" content="586">
</div>
