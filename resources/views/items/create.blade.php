@extends('main')
@section('content')

	<style>
		label {
			margin-top: 10px;
		}
	</style>
	
	<form class="bg0 p-t-75 p-b-85" action="{{ route('insertItem') }}" method="POST">
        <input type="hidden" name="selling_user_id" value="{{ auth()->user()->user_id }}"/>
        <input type="hidden" name="auction_id" value="{{ $auctionId }}"/>
        <input type="hidden" name="category_id" value="{{ $categoryId }}"/>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="col-10">
                            <h2 class="mtext-109 cl2 p-b-30">
                                <b>アイテム追加</b>
                            </h2>
							
                            <div class="form-group">
								<div class="row">
									<div class="col">
                                        <label for="brand_id"><b>ブランド  </b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
                                        <div class="rs1-select2 rs2-select2 bor10 bg0 m-b-12 m-t-9">
                                            <select class="js-select2" style="with:100%" id="brand_id" name="brand_id" value="{{ old('brand_id') }}" placeholder="ブランドを選択してください">
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
                                        <label for="series"><b>シリーズ  </b></label>
                                        <input type="text" class="form-control size-119" name="series" value="{{ old('series') }}" placeholder="シリーズを入力してください" />
                                        @if($errors->has('series'))
                                            <label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('series')}}</label><br/>
                                        @endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col">
                                        <label for="name"><b>名前　 </b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
                                        <input type="text" class="form-control size-119" name="name" value="{{ old('name') }}" placeholder="日本語で名前を入力してください"/>
                                        @if($errors->has('name'))
                                            <label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('name')}}</label><br/>
                                        @endif
									</div>
								</div>
							</div>
                            <div class="form-group">
								<label for="starting_price"><b>始値</b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
								<input type="text" class="form-control size-119" id="starting_price" name="starting_price" value="{{ old('starting_price') }}" placeholder="始値を入力してください">
								@if($errors->has('starting_price'))
									<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('starting_price')}}</label><br/>
								@endif
							</div>
							<div class="form-group">
								<label for="description"><b>内容</b></label>
								<textarea class="form-control" id="description" name="description" rows="2" value="{{ old('description') }}" placeholder="内容を入力してください"></textarea>
							</div>
							<div class="form-group">
								<label for="info"><b>技術の情報</b></label>
								<div class="row">
									<div class="col-lg-10 col-6">
										<ul style="margin-top:20px; margin-left:30px">
											@foreach ($categoryValueName as $key => $value)
												<li class="flex-w flex-t p-b-7">
													<label>{{ $value }}</label>
													<span class="stext-102 cl6 size-206">
														<div class="col-10">
															<input type="text" class="form-control size-119" name="values[{{$key}}]" placeholder="インフォを入力してください"/>
														</div>
													</span>
												</li>
												<br>
												<br>
											@endforeach
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group">
                                <div class="wrap-table-shopping-cart">
                                    <table class="table-shopping-cart">
                                        <tr class="table_head">
                                            <th class="column-1">写真</th>
                                        </tr>
                                        <tr class="table_row">
											@for ($i = 1; $i <=5; $i ++)
												<td class="column-1">
													<div class="input-group">
														<input type="file" class="form-control-file" id="uploaduser-{{$i}}" style="margin-top:20px; margin-right:10px">
														<input type="hidden" name="images[{{$i}}]" id="thumbuser-{{$i}}">
													</div>
													<div id="image_show_user-{{$i}}" style="margin-top:10px">
													</div>
												</td>
											@endfor
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
 
@endsection
