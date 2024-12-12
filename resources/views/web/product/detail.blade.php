@extends('web.index')
@section('title',$product->name)

@section('style_page')
    <link rel="stylesheet" href="{{asset('assets/css/create-order.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/product.css')}}">
@stop
{{--content of page--}}
@section('content')
    @include('web.partials.failed-alert')
    @include('web.partials.warning-alert')
    @php
        $userLogin = session('jwt_token') ? \Tymon\JWTAuth\Facades\JWTAuth::setToken(session('jwt_token'))->authenticate() : null;

         $orderProduct = null;
         if ($userLogin){
            $orderId = \App\Models\OrderModel::where('user_id',$userLogin->id)->pluck('id');
            $orderProduct = \App\Models\OrderProductModel::where('product_id',$product->id)->first();
         }

    @endphp
    <a href="{{route('home')}}" class="line-back">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Trở lại</span>
    </a>
    <div class="product-detail">
        <div class="product-image-wrapper">
            <img src="{{asset($product->src)}}" alt="Product" class="product-image"/>
        </div>
        <div class="product-information">
            <h5 class="product-name">{{$product->name ?? 'N/A'}}</h5>
            <div class="product-field">
                <label class="title-field">Giá thông thường:</label>
                <div class="item-field">{{ number_format($product->price ?? 0, 0, ',', '.') }}đ</div>
            </div>
            <div class="product-field">
                <label class="title-field">Giá chào bán:</label>
                <div class="item-field">{{ number_format($product->price ?? 0, 0, ',', '.') }}đ</div>
            </div>
            <div class="product-field">
                <label class="title-field">Số lượng còn lại:</label>
                <div class="item-field">{{ number_format($product->quantity ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="product-field">
                <label class="title-field">Đã áp dụng giảm giá:</label>
                <div class="item-field">Áp dụng giảm giá theo cấp độ thành viên</div>
            </div>
            <div class="product-field member-wrapper">
                <label class="title-field">Giá chiết khấu theo cấp độ thành viên:</label>
                <div class="member-field">
                    @foreach($memberShip as $member)
                        <div class="member-item">
                            <p>{{$member->name}} ({{$member->discount_percent}}%)</p>
                            <span>{{ number_format($product->price * (100 - $member->discount_percent) / 100, 0, ',', '.') }}đ</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="product-field flex-column">
                <label class="title-field">Cơ sở áp dụng:</label>
                <div class="location-field">
                    @foreach($productLocations as $productLocation)
                        <div class="location-item">
                            <img src="{{asset('assets/images/location-icon.png')}}" alt="Location"
                                 class="location-icon"/>
                            <p class="location-text">{{$productLocation->location ?? 'N/A'}}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="product-field">
                <label class="title-field">Thời hạn sử dụng:</label>
                <div class="item-field">
                    Từ {{ \Carbon\Carbon::parse($product->start_date)->format('d/m/Y') }} -
                    Đến {{ \Carbon\Carbon::parse($product->end_date)->format('d/m/Y') }}
                </div>
            </div>
            <div class="button-wrapper mt-3">
                <a href="javascript:void(0)"
                   class="add-to-cart"
                   data-quantity="{{ $product->quantity ?? 0 }}">Thêm vào giỏ hàng</a>
                @include('web.product.modal-add-cart')
                <a href="javascript:void(0)"
                   class="buy-now"
                   data-quantity="{{ $product->quantity ?? 0 }}"
                   data-route="{{ route('create-order.buy-now.index', ['productID' => $product->id]) }}">Mua ngay</a>
            </div>
        </div>
    </div>
    <div class="related-information-wrapper">
        <div class="related-information-header d-flex justify-content-between">
            <div class="d-flex">
                @php
                    $activeTab = 'detail';
                @endphp
                <div
                    class="product-wrapper {{ $activeTab === 'detail' ? 'active' : '' }}"
                    onclick="setActiveTab('detail')"
                >
                    Chi tiết sản phẩm
                </div>
                <div
                    class="store-wrapper {{ $activeTab === 'guide' ? 'active' : '' }}"
                    onclick="setActiveTab('guide')"
                >
                    Hướng dẫn sử dụng
                </div>
                <div
                    class="store-reviews {{ $activeTab === 'product_reviews' ? 'active' : '' }}"
                    onclick="setActiveTab('product_reviews')"
                >
                    Đánh giá sản phẩm
                </div>
            </div>
        </div>

        <div class="tab-content">
            <div class="detail-tab" style="display: {{ $activeTab === 'detail' ? 'block' : 'none' }};">
                {!! $product->describe ?? '' !!}
            </div>
            <div class="guide-tab" style="display: {{ $activeTab === 'guide' ? 'block' : 'none' }};">
                {!! $product->guide ?? '' !!}
            </div>
            <div class="reviews-tab" style="display: {{ $activeTab === 'product_reviews' ? 'block' : 'none' }};">
                <div class="box-star">
                    <div class="star-left">
                        <p class="title-star">Nhận xét</p>
                        <p class="title-star">Đánh giá trung bình</p>
                        <p class="number-star-all">{{$product->star??0}}</p>
                    </div>
                    <div class="star-right">
                        <p class="title-star">Chi tiết</p>
                        <div class="content_star_all">
                            <div class="rating">
                                <div class="start_line">
                                    <span>5 sao</span>
                                    <div class="progess">
                                        <div class="line_progess">
                                            <div class="progress-bar" style="width: {{$percent_5??0}}%"></div>
                                        </div>
                                    </div>
                                    <p class="point_sao">{{$percent_5??0}}%</p>
                                </div>
                                <div class="start_line">
                                    <span>4 sao</span>
                                    <div class="progess">
                                        <div class="line_progess">
                                            <div class="progress-bar" style="width: {{$percent_4??0}}%"></div>
                                        </div>
                                    </div>
                                    <p class="point_sao">{{$percent_4??0}}%</p>
                                </div>
                                <div class="start_line">
                                    <span>3 sao</span>
                                    <div class="progess">
                                        <div class="line_progess">
                                            <div class="progress-bar" style="width: {{$percent_3??0}}%"></div>
                                        </div>
                                    </div>
                                    <p class="point_sao">{{$percent_3??0}}%</p>
                                </div>
                                <div class="start_line">
                                    <span>2 sao</span>
                                    <div class="progess">
                                        <div class="line_progess">
                                            <div class="progress-bar" style="width: {{$percent_2??0}}%"></div>
                                        </div>
                                    </div>
                                    <p class="point_sao">{{$percent_2??0}}%</p>
                                </div>
                                <div class="start_line">
                                    <span>1 sao</span>
                                    <div class="progess">
                                        <div class="line_progess">
                                            <div class="progress-bar" style="width: {{$percent_1??0}}%"></div>
                                        </div>
                                    </div>
                                    <p class="point_sao">{{$percent_1??0}}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="line-star">
                    <div class="product-rate">
                        <div class="star-rating" style="--rating: {{$product->star??0}};font-size: 16px"></div>
                    </div>
                    @if($userLogin && $orderProduct)
                        <div class="btn-reviews" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <img src="{{asset('assets/images/icon-star.png')}}" alt="">
                            <span>Đánh giá</span>
                        </div>
                    @endif
                </div>

                <div class="content-reviews-star" id="reviews-container">
                    @foreach($reviews as $review)
                        <div class="item-review">
                            <div class="item-top-content-star">
                                <div class="img-avatar">
                                    <img src="{{ $review->user->avatar }}" alt="">
                                    <span>{{ $review->user->full_name }}</span>
                                </div>
                                <div class="star-date">
                                    <div class="product-rate">
                                        <div class="star-rating star-rating-review"
                                             style="--rating: {{ $review->star }}; font-size: 16px"></div>
                                    </div>
                                    <span>{{ $review->created_at->format('H:i d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="item-content-reviews">
                                {{ $review->content }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <input type="text" value="{{$product->id}}" id="product_sp_id" hidden>
                @if ($reviews->hasMorePages())
                    <button class="btn-load-more" id="load-more">Hiển thị thêm đánh giá</button>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal review-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog h-100">
            <form action="{{ route('product-reviews.store') }}" method="POST"
                  class="modal-content modal-content-review">
                @csrf
                <div class="modal-header border-bottom-0 flex-column">
                    <h1 class="modal-title-review" id="exampleModalLabel">Viết đánh giá của bạn</h1>
                    <input type="text" name="product_id" value="{{$product->id}}" hidden>
                    <div class="d-flex justify-content-center align-items-center w-100">
                        <div class="rating-item">
                            <input type="radio" id="rating1" class="start" name="rating" value="1" required>
                            <label for="rating1"></label>
                        </div>
                        <div class="rating-item">
                            <input type="radio" id="rating2" class="start" name="rating" value="2" required>
                            <label for="rating2"></label>
                        </div>
                        <div class="rating-item">
                            <input type="radio" id="rating3" class="start" name="rating" value="3" required>
                            <label for="rating3"></label>
                        </div>
                        <div class="rating-item">
                            <input type="radio" id="rating4" class="start" name="rating" value="4" required>
                            <label for="rating4"></label>
                        </div>
                        <div class="rating-item">
                            <input type="radio" id="rating5" class="start" name="rating" value="5" required>
                            <label for="rating5"></label>
                        </div>
                    </div>
                </div>
                <div class="modal-body pt-1">
                    <span class="title-content-review">Nội dung đánh giá</span>
                    <textarea name="content" class="content-push-review" rows="5"></textarea>
                </div>
                <div class="modal-footer border-top-0 pt-3 d-flex justify-content-center align-items-center gap-2 pb-4">
                    <button type="button" class="btn btn-send-cancel" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-send-review">Đánh giá</button>
                </div>
            </form>
        </div>
    </div>
@stop
@section('script_page')
    <script src="{{asset('assets/js/create-order.js')}}"></script>
    <script src="{{asset('assets/js/product.js')}}"></script>
    <script>
        const price = {{ $product->price }};
        const deleteIcon = "{{ asset('assets/images/trash-icon.png') }}";
        const maxQuantity = {{ $product->quantity ?? 0 }};
        const userLogin = @json($userLogin);
    </script>
@stop
