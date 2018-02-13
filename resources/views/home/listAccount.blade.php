@foreach ($accountList as $item)
    <div class="col-md-3 col-sm-4 col-xsm-4 col-xs-6">
        <div class="list-account">
            <div class="list-account__detail">
                <div class="list-account__detail-thumb" style="background-image: url({{$item->category->game_avatar}});"></div>
                <div class="list-account__detail-title">
                    {{ $item->category->game_name }}
                </div>
                <div class="list-account__detail-desc">
                    <h4 class="title">{{ $item->shop->name }}</h3>
                    {{ $item->description }}
                </div>
                <div class="list-account__detail-action">
                    <select name="" id="time-rent">
                        @foreach (json_decode($item->price, true) as $key => $val)
                            <option value="{{ $key }}">{{ $val }}đ/{{ $key }}h</option>
                        @endforeach
                    </select>
                    <button id="btn-rent" data-id="{{$item->id}}">THUÊ</button>
                </div>
            </div>
        </div>
    </div>
@endforeach