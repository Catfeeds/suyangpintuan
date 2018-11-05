<?php

class wxtest extends Lowxp {
	function __construct() {
		parent::__construct();
	}


	function getuid(){
		$this->load->model('wxapi');
		$this->wxapi->wxoauth("oauth");

		$arr_openid = array(
            "oPpj00_wzjGFbmr13IDQlexYmokI",
            "oPpj00x_zWIvYZb5jaSbxhucEwqE",
            "oPpj009l_YiR6LVo1_qON760S3uw",
            "oPpj00_txV78ID6fKACAWVEJlQNg",
            "oPpj005ac2DxugpEcYBABgPInU-U",
            "oPpj00xG7lqosP84utBhm3mYnM80",
            "oPpj00-cSehlHKikidH-l2M2RAwo",
            "oPpj008-CmCO450s08fEdk4GTXf4",
            "oPpj007Qfl3lz62FLd_TnSQWOjVY",
            "oPpj004mzmDfS_WGiaFVQPbYVpSk",
            "oPpj005ZtWbRXbehbVaz1gjM4CYc",
            "oPpj00-wM43_vwrkQob_zNq5uQns",
            "oPpj00563agqw4awgxKIRr5j8A9s",
            "oPpj0055aGn6u8WiPfs6UT7hwmTI",
            "oPpj005XKtnPmPlnXdvYUkJnWX38",
            "oPpj004raWML0vXhliCqqTGLfVms",
            "oPpj005VpV40Bxau6wwAUaY_87Qg",
            "oPpj009t3d4Tq7KWtc5EpK2c5TB4",
            "oPpj000IEGLDZHgafXaKNPOsBD_o",
            "oPpj00weBB_odpIOpWOSPhCdNfus",
            "oPpj008gv1PqmI5NjgNnMfiEiV6U",
            "oPpj000pR-NdJijm087-4g-UN4W0",
            "oPpj000EeETG0tewsKWn2H3ksF-I",
            "oPpj003rr7DBHa-zqbXuvq3ka6cY",
            "oPpj003pIQIaN0xNP4JymjJT9OAU",
            "oPpj00x3ztPw2K9N0vC9gkIguqAY",
            "oPpj004kX2xxNR-UI_-WAU6_8Gso",
            "oPpj00zD4dgpq61dLxAYXRxSQU_M",
            "oPpj00zHUZ72akKGHwk4wQ_edk3M",
            "oPpj005pRup3Dr7E3meVJIHSsb0s",
            "oPpj001p2zMp6-tc9-7sUyBUyOUU",
            "oPpj008FlR-nsRnQQ_u4GTY5kGrQ",
            "oPpj00xurLQCG7G1krCvsgu2NZow",
            "oPpj00-UBnji8-mjfkVeHaxXVNwA",
            "oPpj00_7G6gi1FWbJ7gM0kYp7h4M",
            "oPpj00yuj1jh_NWzGQK253p_I-Mw",
            "oPpj000eQK6EWKl7O7Qngl7WeVas",
            "oPpj001BkH7bE48Va0LMvcbJdLO0",
            "oPpj00-_5SC7UwcCYAbFxL32SOY0",
            "oPpj005GGsEL0-lxKmFrcBc9nWTQ",
            "oPpj00xlFIXNPXTCZjY6ta_SxoX0",
            "oPpj002Bz1qDJ2We47v-D3tHE9oA",
            "oPpj00znLX0xWaHu9HcdW9KGCgBo",
            "oPpj004VW40EFjrhrva61y7uU8i0",
            "oPpj000QDj2UNfPz57q34_V93LoQ",
            "oPpj002JO-TyBB2c_J0ACIabugiY",
            "oPpj008KwUtgP38Os1V87Qg1kJmU",
            "oPpj00-F9NjQdyueafkSEi_1d9TE",
            "oPpj002CuVSO-66GSDvXrFd7sSPo",
            "oPpj000eOYJWau9FWNSgmW2g9g9A",
            "oPpj00_6IEZMw7-iw6YJ1B6wfc0k",
            "oPpj001ywm8Yo-WPn_xcWaYOFwkk",
            "oPpj00_BaxHaUxee7jvETiVlO0VM",
            "oPpj004s2Xurv8Rz2u8mjzyBjSWw",
            "oPpj009_DoXMHwB8f-Y-SK_8q_T0",
            "oPpj002osFUZAjv-gLfKoVOx-RNY",
            "oPpj00wVvMTwb1-7qOsMofCcgnjE",
            "oPpj00zkTu_DiToqcnllHcun1MmY",
            "oPpj002g7XznNLNvTUrsjdAAfN-A",
            "oPpj003D5IV0BCU8n54KOC61HP3s",
            "oPpj00y5aErQM7nBxPHMJGHu47jw",
            "oPpj004JOsHNVxlFvrTSC6bUET8E",
            "oPpj006WfF1jZMUfhb92ySTrdLGU",
            "oPpj00ySAnJAks40nsz2KwYk2L_4",
            "oPpj00ySoLVmsBdUUO0hOxBMEq54",
            "oPpj003WkPFx5Vn1Hw9cMkNPPYYo",
            "oPpj00y-d4Wis5JWgkJfLi6xPYoM",
            "oPpj00zGR07gaUn4MZkxKatNk0ws",
            "oPpj00z7MbIJAPVa1m88ddjjoTb4",
            "oPpj0054XQ11TQFI_IppWHLzt1v0",
            "oPpj009wEI5vac2nbrI6k1Vzy93o",
            "oPpj00x9kjDQNtbOaiAf2j9T9-kg",
            "oPpj00_XhX9tAC2T_bUk0SPSLSzc",
            "oPpj0017kVcJt98ZSJZrKtz14H88",
            "oPpj00zDAAjEpvq9JG8EyTzZVGis",
            "oPpj00xnZMr7ZS8ibVCnW7PGiBzs",
            "oPpj00zEhHizHD8-qkv6-6fpw9js",
            "oPpj001zi3zj1uL_Bnz6vWNB5YZc"
		);
		foreach($arr_openid as $v){

		    $userinfo = array();
			$userinfo = $this->wxapi->wechat->getUserInfo($v);
			$temp = array();
			$temp['openid'] = $userinfo['openid'];
			$temp['unionid'] = $userinfo['unionid'];
			$mid = $this->db->getstr("select mid from ###_oauth where `type` = 0 and openid='{$temp['openid']}'","mid");

			if($mid>0){
				$u_mid = $this->db->getstr("select mid from ###_oauth where `type` = 3 and  openid='{$temp['unionid']}'","mid");
				if($u_mid>0 && $u_mid!=$mid){
					$this->db->query("delete from ###_oauth where mid={$u_mid}");
					$this->db->query("delete from ###_member where mid={$u_mid}");
					$this->db->query("delete from ###_member_detail where mid={$u_mid}");
                    $this->db->insert("oauth",array("mid"=>$mid,"type"=>3,"create_time"=>RUN_TIME,"status"=>1,"openid"=>$temp['unionid']));
                    echo $v."####".$mid."<br/>";
				}

			}

		}

	}


	function sendmsg_bak(){
        $this->load->model('wxtemplate');
        $data =  array(
            "first"=> "您好，您的订单已经取消。",
            "keyword1"=> "2016071800012",
            "keyword2"=> "pingengduo",
            "remark"=> "请点击“详情“查看更多，如有任何疑问请联系我们。",
            "url"=>""
        );
        $this->wxtemplate->sendTest(396,'order_cancel',$data);
    }
    function sendmsg(){
        $this->load->model('wxtemplate');
        $data =  array(
            "first"=> "您好，您的订单已经取消。",
            "keyword1"=> "2016071800012",
            "keyword2"=> "pingengduo",
            "remark"=> "请点击“详情“查看更多，如有任何疑问请联系我们。",
            "url"=>"http://www.pingengduo.com"
        );
        $this->wxtemplate->sendTest(396,'olG4-FZM',$data);
    }
       
}