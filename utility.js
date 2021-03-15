function OnKey(code,obj){
	if(code == 13 && obj !=''){
		var b = document.getElementById(obj);
		b.form.submitter.value = b.id; // 値の設定
//		b.value = "ログイン";
		b.form.submit();
	}
}

function sbmfnc(b,flg){
//    alert('ここ');
	window.onbeforeunload = null;

	var c = b.id;
	b.form.submitter.value = b.id; // 値の設定
    var d = c.substr( 0, 6);
    switch (c){
		case 'newdate':
			if(window.confirm('登録します。よろしいですか？')){
				b.form.submit();
			}
			break;
		case 'update':
			if(window.confirm('更新します。よろしいですか？')){
				b.form.submit();
			}
			break;
		case 'clear':
			if(window.confirm('入力内容をクリアします。よろしいですか？')){
				b.form.submit();
			}
			break;
		case 'Houkoku':
			if(window.confirm('報告処理後は内容の変更はできません。よろしいですか？')){
				b.form.submit();
			}
			break;
		case 'HoukokuKaijyo':
			if(window.confirm('報告解除します。よろしいですか？')){
				b.form.submit();
			}
			break;
		case 'modoru': case 'logout':
			if(flg == 0){
				if(window.confirm('登録が完了していません。画面を移動してよろしいですか？')){
					b.form.submit();
				}
			}else{
				b.form.submit();
			}
			break;
		case 'UpdateEnd':
			if(window.confirm('1完了処理を行います。よろしいですか？')){
				b.form.submit();
			}
			break;
		case 'delete':
			if(window.confirm('削除します。よろしいですか？')){
				b.form.submit();
			}
			break;
        case 'fileupload': case 'imgupload':
			if(window.confirm('ファイルをアップロードします。よろしいですか？')){
				b.form.submit();
			}
			break;
		default:
            if(d=='delete'){
                    if(window.confirm('ファイルを削除します。よろしいですか？')){
                        b.form.submit();
                    }
            }else{
                b.form.submit();
            }
			break;
	}
}

function checkInputText(txt_obj){
	//テキストインプット内の入力値を変数化
	var str = txt_obj.value;
	//入力値に 0～9 以外があれば
	if(str.match(/[^0-9]+/)){
		//alert("半角数字のみを入力してください。");
		// 0～9 以外を削除
		txt_obj.value = str.replace(/[^0-9]+/g,"");
	}
}
