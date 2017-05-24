{% extends "/headfooter.volt" %}
{% block title %}<title>CRM Manager</title>{% endblock %}
{% block scriptimport %}    

	<script src="/js/crm/crm.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/crm.css" />

	<script src="//js/vendor/jquery.validate.min.js"></script>
	
	<script src="/js/dashboard/chart.min.js"></script>	
	
{% endblock %}
{% block preloader %}
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
{% endblock %}
{% block content %}
    <div id="wrap">
        <div class="container">
			<div class="row">
				<p class="titles">CRM Manager</p>
			</div>
			<div class="row">
				<div class="col-md-12"> 
					<div class="col-md-6"> 
						<select id="selectboxClients">
							<?php echo $clientsSelect ?>
						</select>
						<button id="loadButton" type="button" class="btn btn-info btn-sm">Load Client</button>
					</div>
					<div class="col-md-6"> 
						<button id="createClientButton" type="button" class="btn btn-success btn-sm pull-right">Create Client</button>
					</div>
				</div>
			</div>
				<div class="row" id="rowInfoAggr">
					<form id="createeditform">
						<div class="col-md-3"> 
							<div class="form-group">
								<label for="amanagername">Account Manager Name:</label>
								<input type="text" class="form-control" id="amanagername" name="amanagername"/>
							</div>
						</div>
						<div class="col-md-3"> 
							<div class="form-group">
								<label for="aggrname">Aggregator Name:</label>
								<select class="selectsRow" id="selectboxClientsIds" name="selectboxClientsIds">
									<?php echo $agregatorsSelect ?>
								</select>
							</div>
						</div>
						<div class="col-md-3"> 
							<div class="form-group">
								<label for="aggrname">Aggregator ID:</label>
								<input disabled type="text" class="form-control" id="aggrname" name="aggrname"/>
							</div>
						</div>
						<div class="col-md-3"> 
							<div class="form-group">
								<label for="aggrskype">Aggregator Skype:</label>
								<input type="text" class="form-control" id="aggrskype" name="aggrskype"/>
							</div>
						</div>
						<div class="col-md-3"> 
							<div class="form-group">
								<label for="workemail">Work email:</label>
								<input type="text" class="form-control" id="workemail" name="workemail"/>
							</div>
						</div>
						<div class="col-md-3"> 
							<div class="form-group">
								<label for="aggrname">Ask for numbers:</label>
								<select class="selectsRow" id="selectboxClientsAskfornumbers" name="selectboxClientsAskfornumbers">
								  <option value="1">YES</option>
								  <option value="0">NO</option>
								</select>
							</div>
						</div>
						<div class="col-md-3"> 
							<div class="form-group">
								<label for="aggrname">State:</label>
								<select class="selectsRow" id="selectboxClientsState" name="selectboxClientsState">
								  <option value="1">ACTIVE</option>
								  <option value="0">NOT ACTIVE</option>
								</select>
							</div>
						</div>
						<div class="col-md-3"> 
							<div class="form-group">
								<label for="agglanguage">Language:</label>
								<select class="selectsRow" id="agglanguage" name="agglanguage">
									<option value="om">Afaan Oromoo</option><option value="aa">Afaraf</option><option value="af">Afrikaans</option><option value="ak">Akan</option><option value="an">Aragonés</option><option value="ig">Asụsụ Igbo</option><option value="gn">Avañe'ẽ</option><option value="ae">Avesta</option><option value="ay">Aymar Aru</option><option value="az">Azərbaycan Dili</option><option value="id">Bahasa Indonesia</option><option value="ms">Bahasa Melayu</option><option value="bm">Bamanankan</option><option value="jv">Basa Jawa</option><option value="su">Basa Sunda</option><option value="bi">Bislama</option><option value="bs">Bosanski Jezik</option><option value="br">Brezhoneg</option><option value="ca">Català</option><option value="ch">Chamoru</option><option value="ny">Chicheŵa</option><option value="sn">Chishona</option><option value="co">Corsu</option><option value="cy">Cymraeg</option><option value="da">Dansk</option><option value="se">Davvisámegiella</option><option value="de">Deutsch</option><option value="nv">Diné Bizaad</option><option value="et">Eesti</option><option value="na">Ekakairũ Naoero</option><option value="en">English</option><option value="es">Español</option><option value="eo">Esperanto</option><option value="eu">Euskara</option><option value="ee">Eʋegbe</option><option value="to">Faka Tonga</option><option value="mg">Fiteny Malagasy</option><option value="fr">Français</option><option value="fy">Frysk</option><option value="ff">Fulfulde</option><option value="fo">Føroyskt</option><option value="ga">Gaeilge</option><option value="gv">Gaelg</option><option value="sm">Gagana Fa'a Samoa</option><option value="gl">Galego</option><option value="sq">Gjuha Shqipe</option><option value="gd">Gàidhlig</option><option value="ki">Gĩkũyũ</option><option value="ha">Hausa</option><option value="ho">Hiri Motu</option><option value="hr">Hrvatski Jezik</option><option value="io">Ido</option><option value="rw">Ikinyarwanda</option><option value="rn">Ikirundi</option><option value="ia">Interlingua</option><option value="nd">Isindebele</option><option value="nr">Isindebele</option><option value="xh">Isixhosa</option><option value="zu">Isizulu</option><option value="it">Italiano</option><option value="ik">Iñupiaq</option><option value="pl">Język Polski</option><option value="mh">Kajin M̧ajeļ</option><option value="kl">Kalaallisut</option><option value="kr">Kanuri</option><option value="kw">Kernewek</option><option value="kg">Kikongo</option><option value="sw">Kiswahili</option><option value="ht">Kreyòl Ayisyen</option><option value="kj">Kuanyama</option><option value="ku">Kurdî</option><option value="la">Latine</option><option value="lv">Latviešu Valoda</option><option value="lt">Lietuvių Kalba</option><option value="ro">Limba Română</option><option value="li">Limburgs</option><option value="ln">Lingála</option><option value="lg">Luganda</option><option value="lb">Lëtzebuergesch</option><option value="hu">Magyar</option><option value="mt">Malti</option><option value="nl">Nederlands</option><option value="no">Norsk</option><option value="nb">Norsk Bokmål</option><option value="nn">Norsk Nynorsk</option><option value="uz">O'zbek</option><option value="oc">Occitan</option><option value="ie">Interlingue</option><option value="hz">Otjiherero</option><option value="ng">Owambo</option><option value="pt">Português</option><option value="ty">Reo Tahiti</option><option value="rm">Rumantsch Grischun</option><option value="qu">Runa Simi</option><option value="sc">Sardu</option><option value="za">Saɯ Cueŋƅ</option><option value="st">Sesotho</option><option value="tn">Setswana</option><option value="ss">Siswati</option><option value="sl">Slovenski Jezik</option><option value="sk">Slovenčina</option><option value="so">Soomaaliga</option><option value="fi">Suomi</option><option value="sv">Svenska</option><option value="mi">Te Reo Māori</option><option value="vi">Tiếng Việt</option><option value="lu">Tshiluba</option><option value="ve">Tshivenḓa</option><option value="tw">Twi</option><option value="tk">Türkmen</option><option value="tr">Türkçe</option><option value="ug">Uyƣurqə</option><option value="vo">Volapük</option><option value="fj">Vosa Vakaviti</option><option value="wa">Walon</option><option value="tl">Wikang Tagalog</option><option value="wo">Wollof</option><option value="ts">Xitsonga</option><option value="yo">Yorùbá</option><option value="sg">Yângâ Tî Sängö</option><option value="is">ÍSlenska</option><option value="cs">čEština</option><option value="el">ελληνικά</option><option value="av">авар мацӀ</option><option value="ab">аҧсуа бызшәа</option><option value="ba">башҡорт теле</option><option value="be">беларуская мова</option><option value="bg">български език</option><option value="os">ирон æвзаг</option><option value="kv">коми кыв</option><option value="ky">Кыргызча</option><option value="mk">македонски јазик</option><option value="mn">монгол</option><option value="ce">нохчийн мотт</option><option value="ru">русский язык</option><option value="sr">српски језик</option><option value="tt">татар теле</option><option value="tg">тоҷикӣ</option><option value="uk">українська мова</option><option value="cv">чӑваш чӗлхи</option><option value="cu">ѩзыкъ словѣньскъ</option><option value="kk">қазақ тілі</option><option value="hy">Հայերեն</option><option value="yi">ייִדיש</option><option value="he">עברית</option><option value="ur">اردو</option><option value="ar">العربية</option><option value="fa">فارسی</option><option value="ps">پښتو</option><option value="ks">कश्मीरी</option><option value="ne">नेपाली</option><option value="pi">पाऴि</option><option value="bh">भोजपुरी</option><option value="mr">मराठी</option><option value="sa">संस्कृतम्</option><option value="sd">सिन्धी</option><option value="hi">हिन्दी</option><option value="as">অসমীয়া</option><option value="bn">বাংলা</option><option value="pa">ਪੰਜਾਬੀ</option><option value="gu">ગુજરાતી</option><option value="or">ଓଡ଼ିଆ</option><option value="ta">தமிழ்</option><option value="te">తెలుగు</option><option value="kn">ಕನ್ನಡ</option><option value="ml">മലയാളം</option><option value="si">සිංහල</option><option value="th">ไทย</option><option value="lo">ພາສາລາວ</option><option value="bo">བོད་ཡིག</option><option value="dz">རྫོང་ཁ</option><option value="my">ဗမာစာ</option><option value="ka">ქართული</option><option value="ti">ትግርኛ</option><option value="am">አማርኛ</option><option value="iu">ᐃᓄᒃᑎᑐᑦ</option><option value="oj">ᐊᓂᔑᓈᐯᒧᐎᓐ</option><option value="cr">ᓀᐦᐃᔭᐍᐏᐣ</option><option value="km">ខ្មែរ</option><option value="zh">中文&nbsp;(Zhōngwén)</option><option value="ja">日本語&nbsp;(にほんご)</option><option value="ii">ꆈꌠ꒿ Nuosuhxop</option><option value="ko">한국어&nbsp;(韓國語)</option>
								</select>
							</div>
						</div>
						<div style="margin-left: -14px;" class="col-md-12"> 
							<div class="col-md-3"> 
								<div class="form-group">
									<label for="aggstatus">Status:</label>
									<textarea class="textareacrm" name="aggstatus" id="aggstatus"></textarea>
								</div>
							</div>
							<div class="col-md-3" style="margin-left: 8px;"> 
								<div class="form-group">
									<label for="aggnotes">Notes:</label>
									<textarea class="textareacrm" name="aggnotes" id="aggnotes"></textarea>
								</div>
							</div>
							
							<div class="col-md-3" style="margin-left: 8px;"> 
								<div class="form-group">
									<button id="createNow" type="submit" class="btn btn-danger btn-sm pull-right">CREATE</button>
									<button id="editNow" type="submit" class="btn btn-danger btn-sm pull-right">EDIT</button>
								</div>
							</div>
						</form>
						
					</div>
				</div>
				<div id="status2">&nbsp;</div>
				<div class="row" id="rowchartAggr">
				
						<div class="row" id="ppp">
				
					<div id="greybox1" class="col-md-6 greybox">
      <h1 class="titleGreyBox">LAST 7 DAYS + TODAY</h1>
      <div class="row">
		<div>
			<canvas id="chartDashboard" width="552" height="220"></canvas>
		</div>
      </div>
      <div class="row">
        <p class="circle" id="circleClicks" onclick="chartDash('CLICKS')"></p>
        <p class="graphicsLabel" id="clickGraph" onclick="chartDash('CLICKS')">CLICKS</p>
        <p class="circle" id="circleConversions" onclick="chartDash('CONVERSIONS')"></p>
        <p class="graphicsLabel" id="conversionsGraph" onclick="chartDash('CONVERSIONS')">CONVERSIONS</p>
        <p class="circle" id="circleCr" onclick="chartDash('CR')"></p>
        <p class="graphicsLabel" id="crGraph" onclick="chartDash('CR')">CR (%)</p>
        <p class="circle" id="circleRevenue" onclick="chartDash('REVENUE')"></p>
        <p class="graphicsLabel" id="reveneuGraph" onclick="chartDash('REVENUE')">REVENUE</p>
      </div>
    </div>
    <div class="col-md-6 greybox">
      <h1 class="titleGreyBox">OVERVIEW</h1>
      <table id="tableDash" width="100%" border="0">
        <tbody>
          <tr>
            <th class="colDash" scope="col">&nbsp;</th>
            <th class="colDash" scope="col">REVENUE (USD)</th>
            <th class="colDash" scope="col">CLICKS</th>
            <th class="colDash" scope="col">EPC</th>
          </tr>
          <tr class="lineTable">
            <th class="colLeft" scope="row">TODAY</th>
            <td id="r1c1">&nbsp;00</td>
            <td id="r1c2">&nbsp;00</td>
            <td id="r1c3">&nbsp;00</td>
          </tr>
          <tr class="lineTable">
            <th class="colLeft" scope="row">LAST 3 DAYS</th>
            <td id="r2c1">&nbsp;00</td>
            <td id="r2c2">&nbsp;00</td>
            <td id="r2c3">&nbsp;00</td>
          </tr>
          <tr class="lineTable">
            <th class="colLeft" scope="row">TREND</th>
            <td id="r3c1">&nbsp;00</td>
            <td id="r3c2">&nbsp;00</td>
            <td id="r3c3">&nbsp;00</td>
          </tr>
        </tbody>
      </table>
	  <p id="plabeldes">* Results based on last hour reports.</p>	
    </div>
				</div>
		</div>
	</div>

{% endblock %}
{% block simplescript %}
{% endblock %}