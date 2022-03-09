@include('head')
<style>
	label {
    	margin-top: 10px;
	}
</style>
<body class="animsition">
	
	@include('headerPage')
	
	@include('cart2')


	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.html" class="stext-109 cl8 hov-cl1 trans-04">
				ホーム
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				オークション追加
			</span>
		</div>
	</div>
		
	<form class="bg0 p-t-75 p-b-85" action="{{ route('insertItem') }}" method="POST">
        <input type="hidden" name="selling_user_id" value="{{ auth()->user()->user_id }}"/>
        <input type="hidden" name="auction_id" value="{{ $auctionId }}"/>
        <input type="hidden" name="category_id" value="{{ $categoryId }}"/>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="col-10">
                            <h4 class="mtext-109 cl2 p-b-30">
                                <b>ITem追加</b>
                            </h4>
							
                            <div class="form-group">
								<div class="row">
									<div class="col">
                                        <label for="brand_id"><b>brand  </b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
                                        <div class="rs1-select2 rs2-select2 bor10 bg0 m-b-12 m-t-9">
                                            <select class="js-select2" style="with:100%" id="brand_id" name="brand_id" value="{{ old('brand_id') }}">
                                                @foreach ($brand as $key => $value)
                                                    <option value="{{ $value["brand_id"] }}">{{ $value["brand_id"] }} : {{ $value["name"] }}</option>
                                                @endforeach
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
										@if($errors->has('brand_id'))
											<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('brand_id')}}</label><br/>
										@endif
									</div>
									<div class="col">
                                        <label for="series"><b>series  </b></label>
                                        <input type="text" class="form-control size-119" name="series" value="{{ old('series') }}" />
                                        @if($errors->has('series'))
                                            <label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('series')}}</label><br/>
                                        @endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col">
                                        <label for="name"><b>name  </b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
                                        <input type="text" class="form-control size-119" name="name" value="{{ old('name') }}"/>
                                        @if($errors->has('name'))
                                            <label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('name')}}</label><br/>
                                        @endif
									</div>
									<div class="col">
                                        <label for="name_en"><b>name_en  </b></label>
                                        <input type="text" class="form-control size-119" name="name_en" value="{{ old('name_en') }}" />
                                        @if($errors->has('name_en'))
                                            <label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('name_en')}}</label><br/>
                                        @endif
									</div>
								</div>
							</div>
                            <div class="form-group">
								<label for="title_en"><b>starting_price</b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
								<input type="text" class="form-control size-119" id="starting_price" name="starting_price" value="{{ old('starting_price') }}" placeholder="英語でタイトルを入力してください">
								@if($errors->has('starting_price'))
									<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('starting_price')}}</label><br/>
								@endif
							</div>
							<div class="form-group">
								<label for="description"><b>内容</b></label>
								<textarea class="form-control" id="description" name="description" rows="2" value="{{ old('description') }}"　placeholder="入力してください"></textarea>
							</div>
							<div class="form-group">
                                <div class="wrap-table-shopping-cart">
                                    <table class="table-shopping-cart">
                                        <tr class="table_head">
                                            <th class="column-1">Image</th>
                                            <th>Thong so ky thuat</th>
                                        </tr>
    
                                        <tr class="table_row">
                                            <td class="column-1">
												<div class="input-group">
													<input type="file" class="form-control-file" id="uploaduser" style="margin-top:20px; margin-right:10px">
													<input type="hidden" name="thumbuser" id="thumbuser" value="{{ old('thumbuser') }}">
												</div>
												<div id="image_show_user" style="margin-top:10px">
												</div>
													<br>
													<br>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-lg-10 col-6">
                                                        <ul style="margin-top:20px; margin-left:30px">
                                                            @foreach ($categoryValueName as $key => $value)
                                                                <li class="flex-w flex-t p-b-7">
                                                                    <label>{{ $value }}</label>
                                                                    <span class="stext-102 cl6 size-206">
                                                                        <div class="col">
                                                                            <input type="text" class="form-control size-119" name="{{$key}}"/>
                                                                        </div>
                                                                    </span>
                                                                </li>
                                                                <br>
                                                                <br>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
						</div>
						<button type="submit" class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10" style="float:right">
							登録
						</button>
					
					</div>
				</div>
			</div>
		</div>
		@csrf
	</form>
 
@include('footer')
