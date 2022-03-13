@include('head')
<style>
	label {
    	margin-top: 10px;
	}
</style>
<body class="animsition">
	
	@include('headerPage')
	
	@include('cart2')

    <form class="bg0 p-t-75 p-b-85" action="{{ route('updateUser') }}" method="POST">
        <input type="hidden" name="user_id" value="{{ auth()->user()->user_id }}"/>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="col-10">
                            <h2 class="mtext-109 cl2 p-b-30">
                                <b>アカウント編集</b>
                            </h2>
							
                            <div class="form-group">
								<div class="row">
                                    <div class="col">
                                        <label for="name"><b>名前 </b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
                                        <input type="text" class="form-control size-119" name="name" @if ($user) value="{{ $user->name }}" @else value="{{ old('name') }}" @endif placeholder="名前を入力してください" />
                                        @if($errors->has('name'))
                                            <label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('name')}}</label><br/>
                                        @endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col">
                                        <label for="password"><b>パスワード</b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
                                        <input type="password" class="form-control size-119" name="password" placeholder="パスワードを入力してください"/>
                                        @if($errors->has('password'))
                                            <label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('password')}}</label><br/>
                                        @endif
									</div>
									<div class="col">
                                        <label for="re_pass"><b>再パスワード</b></label>
                                        <input type="password" class="form-control size-119" name="re_pass" placeholder="もう一回パスワードを入力してください"/>
                                        @if($errors->has('re_pass'))
                                            <label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('re_pass')}}</label><br/>
                                        @endif
									</div>
								</div>
							</div>
                            <div class="form-group">
								<label for="email"><b>メール</b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
								<input type="text" class="form-control size-119" id="email" name="email" @if ($user) value="{{ $user->email }}" @else value="{{ old('email') }}" @endif placeholder="メールを入力してください">
								@if($errors->has('email'))
									<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('email')}}</label><br/>
								@endif
							</div>
                            <div class="form-group">
								<label for="phone"><b>電話番号</b><i class="fa fa-asterisk" aria-hidden="true" style="color: red"></i></label>
								<input type="text" class="form-control size-119" id="phone" name="phone" @if ($user) value="{{ $user->phone }}" @else value="{{ old('phone') }}" @endif placeholder="電話番号を入力してください">
								@if($errors->has('phone'))
									<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('phone')}}</label><br/>
								@endif
							</div>
                            <div class="form-group">
								<label for="address"><b>住所</b></label>
								<input type="text" class="form-control size-119" id="address" name="address" @if ($user) value="{{ $user->address }}" @else value="{{ old('address') }}" @endif placeholder="住所を入力してください">
								@if($errors->has('address'))
									<label class="control-label" for="inputError" style="color: red; padding-left: 5px;">{{ $errors->first('address')}}</label><br/>
								@endif
							</div>
                            <div class="form-group">
								<label for="avatar"><b>写真</b></label>
								<div class="input-group">
                                    <input type="file" class="form-control-file" id="uploadAvatar" style="margin-top:20px; margin-right:10px">
                                    <input type="hidden" name="avatar" id="avatar">
                                </div>
                                <div id="image_show_avatar" style="margin-top:10px">
                                @if ($user->avatar)
                                    <img src="{{ $user->avatar }}"  style="max-width:150px; max-height:150px"/>
                                @endif
                                </div>
							</div>
						
						</div>
						<button type="submit" class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10" style="float:right">
							編集
						</button>
					
					</div>
				</div>
			</div>
		</div>
		@csrf
	</form>
 
@include('footer')
