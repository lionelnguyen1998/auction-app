@extends('main')
@section('content')

<style>
	label {
    	margin-top: 10px;
	}
</style>
<!-- Shoping Cart -->
<form class="bg0 p-t-75 p-b-85" action="{{ route('insertAuction') }}" method="POST">
	<input type="hidden" name="selling_user_id" value="{{ auth()->user()->user_id }}"/>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="m-l-25 m-r--38 m-lr-0-xl">
					<div class="col-10">
					<h2 class="mtext-109 cl2 p-b-30">
						<b>オークション追加</b>
					</h2>
						<div class="form-group">
							<label for="category_id"><b>カテゴリー  </b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
							<div class="rs1-select2 rs2-select2 bor10 bg0 m-b-12 m-t-9">
								<select class="js-select2" style="with:100%" id="category_id" name="category_id" value="{{ old('category_id') }}">
									@foreach ($category as $key => $value)
										<option value="{{ $value["category_id"] }}">{{ $value["category_id"] }} : {{ $value["name"] }}</option>
									@endforeach
								</select>
								<div class="dropDownSelect2"></div>
							</div>
							@if($errors->has('category_id'))
								<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('category_id')}}</label><br/>
							@endif
						</div>
						<div class="form-group">
							<label for="title_ni"><b>タイトル（日本語） </b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
							<input type="text" class="form-control size-119" id="title_ni" name="title_ni" placeholder="日本語でタイトルを入力してください" value="{{ old('title_ni') }}">
							@if($errors->has('title_ni'))
								<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('title_ni')}}</label><br/>
							@endif
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col">
								<label for="start_date"><b>始まる時間  </b><i class="fa fa-calendar" aria-hidden="true"></i></label>
								<input type="text" class="form-control size-119" name="start_date" value="{{ old('start_date') }}"/>
								@if($errors->has('start_date'))
									<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('start_date')}}</label><br/>
								@endif
								</div>
								<div class="col">
								<label for="end_date"><b>終わる時間  </b><i class="fa fa-calendar" aria-hidden="true"></i></label>
								<input type="text" class="form-control size-119" name="end_date" value="{{ old('end_date') }}" />
								@if($errors->has('end_date'))
									<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('end_date')}}</label><br/>
								@endif
								</div>
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
