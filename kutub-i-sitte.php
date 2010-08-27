<?php
/*
Plugin Name: Kütüb-i Sitte
Plugin URI: http://wordpress.org/extend/plugins/kutub-i-sitte/
Description: Hadis mecmualarının en sahihleri kabul edilen; Buhârî ve Müslim'in el-Câmiu's-Sahih'leri ile Ebu Davud, Tirmizi, Nesâî ve ibn Mâce'nin Sünen'leri'nde yer alan yaklaşık 7300 Hadis-i Şerifi sitenize ekleyerek bloğunuzu bir hadis portalına çevirebilirsiniz.
Version: 1.0
Author: Süleyman ÜSTÜN
Author URI: http://suleymanustun.com
*/

function ks_menu() {
	$menu = '<ul id="ks_menu" class="clear">';
	$menu .= '<li><a href="?page_id='.$_GET['page_id'].'">Kütüb-i Sitte</a></li>';
	$menu .= '<li><a href="?page_id='.$_GET['page_id'].'&page=hadis">Hadis</a></li>';
	$menu .= '<li><a href="?page_id='.$_GET['page_id'].'&page=hakkinda">Hakkında</a></li>';
	$menu .= '</ul>';
	return $menu;
}

function ks_search() {
	$form = '<fieldset>';
	$form .= '<legend>Kelime Arama</legend>';
	$form .= '<input type="text" name="word" id="word"><input type="submit" value="ARA" onclick="window.location.href=\'?page_id='.$_GET['page_id'].'&word=\'+document.getElementById(\'word\').value">';
	$form .= '</fieldset>';
	return $form;
}

function ks_result() {
	$file = @fopen("wp-content/plugins/kutub-i-sitte/kutub-i-sitte.txt", "r");
	if ($file) {
		$result = '<ul>';
		if (strlen($_GET['word'])<=3) {
			$result .= '<li>Arayacağınız kelime 3 harften büyük olmalıdır..!</li>';
		} else {
			while (!feof($file)) {
				$rows = fgetcsv($file, 20000, "|");
				if (strpos($rows[4],$_GET['word'])) {
					$result .= '<li>'.$rows[4].'<p style="text-align:right">'.$rows[3].' - '.$rows[2].'</p></li>';
				}
			};
		}
		$result .= '</ul>';
		fclose($file);
	}
	return $result;
}

function ks_chapters($value) {
	$chapters = array();
	$file = @fopen("wp-content/plugins/kutub-i-sitte/kutub-i-sitte.txt", "r");
	if ($file) {
		while (!feof($file)) {
			$rows = fgetcsv($file, 20000, "|");
			if (!in_array($rows[0], $chapters)) {
				array_push($chapters, $rows[0]);
			}
		};
		fclose($file);
	}

	asort($chapters);
	$html = '<select style="width:100%" onchange="window.location.href=\'?page_id='.$_GET['page_id'].'&chapter=\'+this.options[this.selectedIndex].text">';
	$html .= '<option>BÖLÜMLER</option>';
	foreach ($chapters as $chapter) {
		if ($chapter==$value) {
			$html .= '<option selected>'.$chapter.'</option>';
		} else {
			$html .= '<option>'.$chapter.'</option>';
		}
	}
	$html .= '</select>';
	return $html;
}

function ks_topics($value) {
	$topics = array();
	$file = @fopen("wp-content/plugins/kutub-i-sitte/kutub-i-sitte.txt", "r");
	if ($file) {
		while (!feof($file)) {
			$rows = fgetcsv($file, 20000, "|");
			if ($rows[0]==$_GET['chapter']) {
				if (!in_array($rows[1], $topics)) {
					array_push($topics, $rows[1]);
				}
			}
		};
		fclose($file);
	}
	
	asort($topics);
	$html = '<select style="width:100%" onchange="window.location.href=\'?page_id='.$_GET['page_id'].'&chapter='.$_GET['chapter'].'&topic=\'+this.options[this.selectedIndex].text">';
	$html .= '<option>KONULAR</option>';
	foreach ($topics as $topic) {
		if ($topic==$value) {
			$html .= '<option selected>'.$topic.'</option>';
		} else {
			$html .= '<option>'.$topic.'</option>';
		}
	}
	$html .= '</select>';
	return $html;
}

function ks_hadiths() {
	$file = @fopen("wp-content/plugins/kutub-i-sitte/kutub-i-sitte.txt", "r");
	if ($file) {
		$html = '<ul>';
		while (!feof($file)) {
			$rows = fgetcsv($file, 20000, "|");
			if ($rows[0]==$_GET['chapter']) {
				if ($rows[1]==$_GET['topic']) {
					$html .= '<li>'.$rows[4].'<p style="text-align:right">'.$rows[3].' - '.$rows[2].'</p></li>';
				}
			}
		};
		$html .= '</ul>';
		fclose($file);
	}
	return $html;
}

function ks_page_hadis() {
	$html = '<h4>Hadis</h4>';
	$html .= '<p>"Hz Peygamber (sav)\'in sözleri, fiilleri, takrirleri ile ahlâkî ve beşerî vasıflarından oluşan sünnetinin söz veya yazı ile ifade edilmiş şekli Bu mânâda hadis, sünnet ile eş anlamlıdır.</p>';
	$html .= '<p>Hadis kelimesi, "eski"nin zıddı "yeni" anlamına geldiği gibi, söz ve haber anlamlarına da gelir Bu kelimeden türeyen bazı fiiller ise haber vermek, nakletmek gibi anlamlar ifade eder Hadis kelimesi, Kur\'ân\'da bu anlamları ifade edecek biçimde kullanılmıştırSözgelimi, "Demek onlar bu söze (hadis) inanmazlarsa, onların peşinde kendini üzüntüyle helak edeceksin" (el-Kehf, 18/6) âyetinde "söz" (Kur\'ân); "Musa\'nın haberi (hadîsu Musa) sana gelmedi mi?" (Tâhâ, 20/9) ayetinde "haber" anlamına gelmektedir "Ve Rabbinin nimetini anlat (fehaddis)" fiili de "anlat, haber ver, tebliğ et" anlamında kullanılmıştır.</p>';
	$html .= '<p>Hadis kelimesi zamanla, Hz Peygamber\'den rivayet edilen haberlerin genel adı olarak kullanılmaya başlanmıştır Kelime, bizzat Rasûlullah (sav) tarafından da, bu anlamda kullanılmıştır Buhârî\'de yeralan bir hadîse göre Ebû Hüreyre, "Yâ Rasûlullah, kıyamet günü şefâatine nail olacak en mutlu insan kimdir?" diye sorar.</p>';
	$html .= '<p>Hz Peygamber şöyle cevap verir: "Senin "hadîse" karşı olan iştiyakını bildiğim için, bu hadis hakkında herkesten önce senin soru soracağını tahmin etmiştim Kıyamet günü şefaatime nail olacak en mutlu insan, "La ilahe illallah" diyen kimsedir". (Buhârî, ilim; 33)</p>';
	$html .= '<p>Hadisin Dindeki Yeri ve Önemi:</p>';
	$html .= '<p>Rasûlullah (sav), Allah\'tan aldığı vahyi yalnızca inanlara aktarmakla kalmamış, aynı zamanda onları açıklamış ve kendi hayatında da tatbik ederek müşahhas örnekler hâline getirmiştir Bu nedenle O\'na "yaşayan Kur\'ân" da denilmiştir İslâm bilginleri genellikle, dinî konularla ilgili hâdislerin Allah tarafından Hz Peygamber\'e vahyedilmiş olduklarını kabul ederler; delil olarak da, "O (Peygamber), kendiliğinden konuşmaz; O\'nun sözleri, kendisine inderilmiş -vahiyden başkası değildir" (en-Necm, :3-4) âyetini ileri sürerler Ayrıca, "Andolsun ki; Allah, mû\'minlere büyük lütufta bulundu Çünkü, daha önce apaçık bir sapıklık içinde bulunuyorlarken, kendi araladan, onlara kitap ve hikmeti öğreten bir elçi gönderdi" (Âlu Irnrân, 3/164) âyetinde sözü edilen "hikmet" kelimesinin, "sünnet" anlamında olduğunu da belirtmişlerdir Nitekim, Hz Peygamber ve O\'nun ashabından nakledilen bazı haberler de, bu gerçeği ortaya koymaktadır.</p>';
	$html .= '<p>Rasûlullah\'tan (sav) şöyle rivayet edilmiştir: "Bana kitap (Kur\'ân) ve bir de onunla birlikte, onun gibisi (sünnet) verildi" (Ebû Dâvûd, Sünen, II, 505) Hassan İbn Atiyye, aynı konuda şu açıklamayı yapmıştır: "Cibrîl (as) Rasûlullah (sav)\'e Kur\'ân\'ı getirdiği ve öğrettiği gibi, sünneti de öylece getirir ve öğretirdi". (İbn Abdilberr, Câmiu\'l Beyâni\'l-ilm, II, 191)</p>';
	$html .= '<p>Yukarıda zikredilen âyet ve haberlerden de anlaşılacağı gibi, Kur\'ân ve hadîs (daha geniş ifadesiyle sünnet), Allah (cc) tarafından Rasûlullah (sav)\'a gönderilmiş birer vahiy olmak bakımından aynıdırlar Şu kadar var ki; Kur\'ân, hadîsin aksine, anlam ve lâfız yönünden bir benzerinin meydana getirilmezliği (i\'câz) ve Levh-i Mahfûz\'da yazı ile tesbit edildiği için, ne Cibrîl (as)\'in ve ne de Hz Peygamber\'in, üzerinde hiçbir tasarrufları bulunmaması noktasında hadîsten ayrılır Hadîs ise, lâfız olarak vahyedilmediği için, Kur\'ân lâfzı gibi mu\'ciz olmayıp, ifade ettiği anlama bağlı kalmak şartıyla sadece mânâ yönüyle nakledilmesi caizdir.</p>';
	$html .= '<p>Hz Peygamber\'den hadîs olarak nakledilen, fakat daha ziyade, O\'nun (sav) sade bir insan sıfatıyla, dinî hiçbir özelliği bulunmayan, günlük yaşayışıyla ilgili sözlerinin, yukarıda anlatılanların dışında kaldığını söylemek gerekir O\'nun (sav) bir insan sıfatıyla hata yapabileceğini açıklaması (Müslim, Fedâil, 139-140-141) bunu gösterir Nitekim bazı ictihadlarında hataya düşmesi, bu konularda herhangi bir vahyin gelmediğini gösterir Ancak bu hataların da, bazan vahiy yolu ile düzeltildiği unutulmamalıdır.</p>';
	$html .= '<p>Vahye dayalı bir fıkıh kaynağı olarak hadis, Kur\'ân karşısındaki durumu ve getirdiği hükümler açısından şu şekillerde bulunur:</p>';
	$html .= '<p>1 Bazı hadisler, Kur\'ân\'in getirdiği hükümleri teyid ve tekit eder Ana-babaya itaatsizliği, yalancı şahitliği, cana kıymayı yasaklayan hadisler böyledir.</p>';
	$html .= '<p>2 Bir kısmı hadisler, Kur\'ân\'ın getirdiği hükümleri açıklar, onları tamamlayıcı bilgiler verir Kur\'ân\'da namaz kılmak, haccetmek, zekât vermek emredilmiş, fakat bunların nasıl olacağı belirtilmemiştir Bu ibadetlerin nasıl yapılacağını hadislerden öğreniyoruz.</p>';
	$html .= '<p>3 Bazı hadisler de, Kur\'ân\'ın hiç temas etmediği konularda, hükümler koyar Hadîsin başlı başına müstakil bir teşri\' (yasama) kaynağı olduğunu gösteren bu tür hadislere, ehlî merkeplerle yırtıcı kuşların etinin yenmesini haram kılan, diyetlerle ilgili birçok hükmü belirten hadisler örnek olarak verilebilir.</p>';
	return $html;
}

function ks_page_hakkinda() {
	$html = '<h4>Kütüb-i Sitte</h4>';
	$html .= '<p>Meşhur altı sahih hadis kitabı. Hadis mecmualarının en sahihleri kabul edilen; Buhârî ve Müslim\'in el-Câmiu\'s-Sahih\'leri ile Ebu Davud, Tirmizi, Nesâî ve ibn Mâce\'nin Sünen\'leri; hadis tasnifinin altın çağı olan Hicrî üçüncü yüzyılda telif edilmiş olmak, mümkün mertebe sahih hadisleri ihtiva etmek, konulara göre tasnif edilmek (alelebvâb)... gibi ortak özelliklerinden dolayı, daha sonraki asırlarda Kütüb-i Sitte: Altı Kitap, ortak adıyla şöhret bulmuştur. Bazı âlimler, az da olsa zayıf ve mevzu hadisler ihtiva ettiği için İbn Mâce\'nin Sünen\'i yerine İmam Mâlik\'in Muvatta\'ı veya Dârimî\'nin Sünen\'ini Kütüb-i Sitte\'nin altıncı kitabı kabul etmişlerdir.</p>';
	$html .= '<p>Buhârî ve Müslim\'in Câmi\'leri, Sahıhayn (İki Sahih) adıyla, müellifleri daha hayattayken büyük bir üne kavuşmuş, bunları Ebu Davud, Tirmizî ve Nesâi\'nin Sünen\'leri takip etmiş ve hadis âlimleri arasında bu kitaplar Usûl-i Hamse (Beş Temel) diye büyük bir kabul görmüştü. Ebu\'l-Fazl Muhammed İbn Tahir el-Makdisî (H.507)\'nin Usûl-i Hamse\'ye yazdığı ve sahabeyi alfabetik olarak sıralamak suretiyle onlardan nakledilen, belirli kitaplardan bulunan hadislerin kaynağını göstermek için yazıları kitaplar anlamına gelen etrafa İbn Mâce\'nin Sünen\'ini de eklemesi ve Şurûti\'l-Ümmeti\'s-Sitte (Altı İmamın Şartları) adlı kitabını telifiyle muteber hadis kitaplarının grub adı bundan sonra, İbn Mâce\'nin de ilave edilmesiyle Kütüb-i Sitte olarak meşhur olmuştur.</p>';
	$html .= '<p>Böyle bir isimlendirme, Kütüb-i Sitte içinde zayıf, hatta mevzu (bilhassa İbn Mâce\'de) hiç bir hadis bulunmadığı, onlar dışındaki hadis kitaplarında da sahih hadis olmadığı anlamına gelmez. Nitekim Buhârî ve Müslim başta olmak üzere, Kütüb-i Sitte müelliflerinden hiç biri, kendi kitaplarına sahih hadislerin tamamını aldıkları, kitaplarındakilerin dışında sahih hadis bulunmadığı şeklinde bir iddiada bulunmamışlardır. Esasen bir hadisin sıhhati, hangi kitapta bulunduğuna bakarak değil, onu nakleden kişilerin haline bakılarak tesbit edilebilir. Diğer taraftan bu altı imam, kendilerinden önce derlenmiş olan yazılı ve sözlü hadis kaynaklarından yararlanarak bu eserleri meydana getirmişlerdir. Bu değerli eserlerin tasnifine, kendilerinden önceki çalışmalar zemin hazırladığı gibi, hadis tasnifi onlardan sonra da devam etmiştir.</p>';
	$html .= '<p>İlmî çevrelerde büyük bir kabul gören Kütüb-i Sitte ile ilgili çok sayıda ve hacimli çalışmalar yapılmıştır. Bunların büyük bir kısmı bu kitapların şerhi (açıklaması), ravilerinin durumları, cem (mükerrerleri çıkararak birlikte rivayet ettikleri hadisleri bir araya toplama) ile ilgilidir. Kütüb-i Sitte hadislerini bir araya toplayıcı çalışmalardan biri, Beğavî\'nin (H.516) Mesâbîhu\'s-Sünne\'sidir. Hadisleri, senedlerini hazfederek kitabına alan Beğavî eserini Sünen tarzında tasnif etmiş, Kütüb-i Sitte ve Dârimî\'nin Sünen\'inde bulunan hadisleri 4434 hadiste toplamıştır. Bu konuda yapılan önemli bir çalışma da İbnu\'l-Esîr\'in (H.606) Câmiu\'l-Usûl li Ehâdisi\'r-Rasûl isimli eseridir. İbnu\'l-Esîr, İbn Mâce hariç Kütüb-i Sitte ile Muvatta\'da bulunan hadisleri,-mükerrerlerini çıkararak- alfabetik tarzda tertib ettiği kitaplar ve onların alt başlıkları olan bablar halinde tasnif etmiştir. 9523 hadis bulunan bu eser Kütüb-i Sitte adıyla Türkçeye tercüme edilmiştir. Kütüb-i Sitte\'yi oluşturan kitaplar ve özellikleri:</p>';
	$html .= '<p>1. Buhârî ve el-Câmiu\'s-Sahîh\'i: Ebu Abdullah Muhammed b. İsmail el-Buhârî (H. 194-256/M.810-870) 40 yıl süren ilmî seyahatler esnasında toplamış olduğu engin hadis malzemesini 16 yılda tasnif ederek, "el-Câmiu\'s Sahîhu\'l-Müsnedü\'l-Muhtasar min Umûri Rasûlillahi (s.a.s) ve Sünenihi ve Eyyâmih" adlı eserini yazmıştır. Hocası İshak b. Rahuye\'nin, "Rasûlüllah\'ın sahih hadislerini muhtasar bir kitapta toplasanız" tavsiyesiyle hareket eden Buhârî, 600.000 hadis arasında seçtiği 7275 hadisi, 97 kitap ve 3400 den fazla bab\'a (alt bölüm) yerleştirmiş, konuları geldikçe aynı hadisi bir kaç yerde daha tekrar etmiştir. Bu nedenle, mükerrerler dışındaki toplam hadis sayısı 3-4 bin civarına inmektedir. Buhârî, tercüme denilen bab başlıklarında konuyla ilgili âyet ve hadislerden iktibaslar yapar, âlimlerin ve bazan kendisinin görüşlerine yer verir, direkt veya endirekt yollarla tercihlerini ihsas ettirir. Tercemelerde verdiği hadis ve haberlerin çoğu muallak (senedsiz veya eksik senedli)tır. Daha önceki hadis mecmualarında pek görülmeyen bu usul Buhâri\'ye hastır. Bu nedenle, "Buhârî\'nin fıkhı tercemelerindedir" sözü yaygınlaşmıştır. (Yekünü 1341 olan bu tür) muallak hadisler, Buhârî\'nin kitabına verdiği isimden de anlaşılacağı gibi, sahih hadislerin dışındadır. Tercümelerde Buhârî\'nin verdiği bilgiler, hadislerin ihtiva ettiği fıkhı malumatı kavramada çok faydalıdır. Bütün âlimlerin ittifakıyla hadis mecmualarının ensahihi kabul edilen el-Câmiu\'s-Sahîh, türkçeye de tercüme edilmiş, mükerrerlerinin çıkarıldığı Tecrid\'i de tercüme ve şerhiyle, Diyanet İşleri Başkanlığınca basılmıştır.</p>';
	$html .= '<p>2. Müslim\'in el-Câmiu\'s-Sahîh\'i: Ebu\'l-Hüseyn Müslim b. Haccâc (H.202-261), 300.000 hadis arasından seçerek tasnif ettiği kitabına, "el-Camiu\'l-Müsnedü\'s-Sahîh" ismini vermiş, mukaddimede tasnif metodunu açıklamıştır. Buhârî\'nin yaptığı gibi bab başlıklarında bilgi vermemiş, hatta, bab başlığı dahi tanzim etmemiş, sadece "bab" demekle yetinmiştir. Bugün eldeki Müslim nüshalarında bulunan bab başlıkları, eseri şerheden İmam Nevevî\'ye aittir. Müslim kitabına, mevkuf ve maktu hadisleri almamış, muallaklara ise çok az yer vermiş, hadisleri konularına göre bölmemiş, hadisi en çok ilgili olduğu yerde nakletmiş, metin ve sened olarak benzerlerini bir arada ve kısaltarak tekrar etmiştir. Bu yönüyle Müslim Buhârî\'den daha derli topludur. Bu ve benzeri özelliklerinden dolayı bazı âlimler (mesela Mağribliler) Müslim\'i Buhâri\'ye tercih etmişlerdir. Müslim\'in Câmi\'i, 54 kitap, 1322 bab, mükerrerler dışında 3033 hadis ihtiva etmektedir. Kadı İyaz ve İmam Nevevî başta olmak üzere pek çok âlim Müslim\'i şerhetmiştir. Müslim, sade, metin ve şerhli olarak türkçeye tercüme edilmiştir.</p>';
	$html .= '<p>3. Tirmizi\'nin Câmi\'i: Ebu İsa Muhammed b. İsa et-Tirmizi\'nin (H: 209-279) Cami\'i, es sünen ismiyle de maruftur. Devrin âlimlerinin tetkikine sunuları ve takdir edilen Sünen-i Tirmizi, 46 kitap, 2496 bab ve 4000 hadis ihtiva etmektedir. Hadisçilik açısından Müslim\'e, fıkhu\'l-hadis (hadislerde bulunan çeşitli hükümler) yönünden de Buhârî\'ye ait özellikleri, onlara yakın ölçüde kitabında toplayan Tirmizi, bab başlığı altında hadisleri sıraladıktan sonra şu işlemleri yapar; hadisin sıhhat durumunu (sahih, hasen, zayıf, hasen-sahih, garib...), ravilerin durumunu, varsa seneddeki illetleri, hadisin diğer tariklerini, sahabilerin o konudaki başka rivayetlerini, bu hadislerle ulemânın nasıl amel ettiğini, ittifak ve ihtilaflarını... açıklar. Hadislerden istifade için çok faydalı olan bu açıklamalar onları, amel edilebilir hale getirir. Tirmizi üzerine de pek çok şerh yazılmış ve eser türkçeye tercüme edilmiştir.</p>';
	$html .= '<p>4. Ebu Davud\'un Sünen\'i: Ebu Davud Süleyman b. Eş\'as es-Sicistânî\'nin (H: 202-275) kitabı, ahkâmla ilgili hadislerin tasnif edildiği Sünen türünün en güzel örneğidir. Kitabına, 400.000 hadis arasından seçtiği 4000 hadisi aldığını, bunların da dört hadiste özetlenebileceğini belirten Ebu Davud; sahih, hasen, leyyin ve amel edilebilir derecedeki zayıf hadisleri Sünen\'ine aldığını söyler. Kitabında zayıf hadislerin mevcudiyetini kabul eden Ebu Davud, muhaddislerin ittifakla terkettikleri herhangi bir hadisi Sünen\'ine almamıştır. 40 kitaptan oluşan Sünen\'e pek çok şerh yazılmış, eser türkçeye de tercüme edilmiştir.</p>';
	$html .= '<p>5. Nesâî\'nin Sünen\'i: Ebu Abdurrahman Ahmed b. Şuayb en-Nesâî (H: 215-303), sahih ve zayıf hadislerden derlediği es-Sünenü\'l-Kübrâ\'sını istek üzerine, sadece sahih hadisleri almak üzere ihtisar etti ve bu yeni eserine el-Müctebâ adını verdi. Kütüb-i Sitte içinde Nesâî denince, işte bu Müctebâ kasdedilir. Sünenler içinde en az zayıf hadis ve cerhedilmiş ravisi olan mücteba, Sahihayn\'dan sonra üçüncü kitap olarak kabul edilir. Nesâî, hadisler arasındaki çok küçük rivayet farklarını dahi göstermiş ve rical tenkidinde büyük bir hassasiyet göstermiştir. 51 kitap ve yaklabıh 2400 babtan oluşan Müctebâ, türkçeye çevrilmiştir.</p>';
	$html .= '<p>6. İbn Mâce\'nin Sunen Ebu Abdullah Muhammed b. Yezıd el-Kazvînî\'nin (H: 209-273) Sünen\'i, 37 kitap, 1515 bab ve 4341 hadis ihtiva eder. Bu hadislerin büyük bir çoğunluğu, diğer beş kitapta (usûli hamse) mevcuttur veya sahih ve hasen durumundadır. ibn Mâce\'deki hadislerin 613 ünün isnadı zayıf, 99 unun isnadı ise, yok hükmünde veya münker ya da yalanlanmıştır. Bilhassa, şahıs, kabile ve şehirlerin faziletleriyle ilgili hadislerin çoğunun uydurma olduğu söylenmiştir. Ancak, VI. asırdan sonra Kütüb-i Sitte\'nin altıncı kitabı olarak kabul edilen İbn Mâce, tertibi, tekrardan uzak ve kısa olusu ile oldukça değerlidir. Muhammed Fuad Abdülbâkı tahkikiyle yapılan baskı, pek çok ilmî kolaylıklar sağlamış, eserdeki zayıf yönlere işaret edilmiştir. Bu baskı esas alınarak Sünen, şerhi de yapılmak suretiyle türkçeye çevrilmiştir.</p>';
	return $html;
}

add_filter('the_content', 'ks_tag');

function ks_tag($content) {
	$content = preg_replace('@\[kutub-i-sitte\]@', '<div id="kutub-i-sitte">'.ks_page().'</div>', $content);
	return $content;
}

function ks_page() {
	$page = ks_menu();
	if ($_GET['page']=='hakkinda') {
		$page .= ks_page_hakkinda();
	} elseif ($_GET['page']=='hadis') {
		$page .= ks_page_hadis();
	} else {
		$page .= ks_search();
		if ($_GET['word']) {
			$page .= '<fieldset>';
			$page .= '<legend>Hızlı Arama</legend>';
			$page .= ks_chapters($_GET['chapter']);
			$page .= ks_topics($_GET['topic']);
			$page .= '</fieldset>';
			$page .= ks_result();
		} elseif ($_GET['topic']) {
			$page .= '<fieldset>';
			$page .= '<legend>Hızlı Arama</legend>';
			$page .= ks_chapters($_GET['chapter']);
			$page .= ks_topics($_GET['topic']);
			$page .= '</fieldset>';
			$page .= ks_hadiths();
		} elseif ($_GET['chapter']) {
			$page .= '<fieldset>';
			$page .= '<legend>Hızlı Arama</legend>';
			$page .= ks_chapters($_GET['chapter']);
			$page .= ks_topics(null);
			$page .= '</fieldset>';
		} else {
			$page .= '<fieldset>';
			$page .= '<legend>Hızlı Arama</legend>';
			$page .= ks_chapters(null);
			$page .= '</fieldset>';
		}
	}
	return $page;
}

add_action('wp_print_styles', 'ks_stylesheet');

function ks_stylesheet() {
	$ks_style_url = WP_PLUGIN_URL . '/kutub-i-sitte/style.css';
	$ks_style_file = WP_PLUGIN_DIR . '/kutub-i-sitte/style.css';
	if ( file_exists($ks_style_file) ) {
		wp_register_style('ks-style-sheets', $ks_style_url);
		wp_enqueue_style('ks-style-sheets');
	}
}
?>