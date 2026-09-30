<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\ProfileEntry;

class AboutController extends Controller
{
    public function profile()
    {
        $isEn = app()->getLocale() === 'en';

        $heading = Setting::get($isEn ? 'profile_heading_en' : 'profile_heading')
            ?: Setting::get('profile_heading', 'CAFER BOZKURT');

        $body = Setting::get($isEn ? 'profile_body_en' : 'profile_body')
            ?: Setting::get('profile_body', static::defaultBody());

        $image = Setting::get('profile_image');
        $current = 'profile';

        return view('about.page', compact('heading', 'body', 'image', 'current'));
    }

    public function studio()
    {
        $isEn = app()->getLocale() === 'en';

        $body = Setting::get($isEn ? 'studio_body_en' : 'studio_body')
            ?: Setting::get('studio_body', static::defaultStudioBody());

        $image = Setting::get('studio_image');
        $heading = 'STÜDYO';
        $current = 'studio';

        return view('about.page', compact('heading', 'body', 'image', 'current'));
    }

    public function team()
    {
        $members = TeamMember::where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('about.team', compact('members'));
    }

    public function awards()
    {
        $entries = ProfileEntry::awards()
            ->where('is_active', true)
            ->with('projects')
            ->orderBy('order')
            ->orderByDesc('id')
            ->get();

        return view('about.entries', ['entries' => $entries, 'current' => 'awards']);
    }

    public function publications()
    {
        $entries = ProfileEntry::publications()
            ->where('is_active', true)
            ->with('projects')
            ->orderBy('order')
            ->orderByDesc('id')
            ->get();

        return view('about.entries', ['entries' => $entries, 'current' => 'publications']);
    }

    public static function defaultBody(): string
    {
        return <<<'HTML'
<p>1945'te İstanbul'da doğan Cafer Bozkurt, 1968'de İTÜ Mimarlık Fakültesi'nden birincilikle mezun oldu. 1971'de kurduğu mimari büro, başkanı olduğu Cafer Bozkurt Mimarlık Ltd. Şirketi olarak devam etmektedir.</p>
<p>Kurulduğu günden beri yurtiçi ve yurtdışında Konut Yerleşmeleri, Turizm ve Rekreasyon Yapıları, Kültür ve Eğitim Yapıları gibi alanlarda yoğunlaşan çok sayıda proje yapmış ve gerçekleştirmiştir. Bunların yanı sıra Tarihi Çevre ve restorasyon konularında, verdiği çok sayıda eser ile uzmanlaştı. Gerçekleşmiş toplam dokuz projesi Aga Khan Ödülleri'ne aday gösterildi. Saki Ali Paşa Hamamı Restorasyonu ile Avrupa Nostra Ödülleri 2017 kapsamında Panorama Kategorisinde ödül sahibi oldu. Çeşitli yapıları 4 defa TSMD Ulusal Mimarlık ödülü aldı. Ayrıca Türkiye Mimarlar Birliği Ödülleri, THBB Mimarlık Ödülleri, The Chicago Athenaeum International Architecture Awards ve Green Good Design Awards gibi ulusal ve uluslararası mimarlık ödülleri kazandı.</p>
<p>Kariyeri boyunca mimarlık mesleği ile ilgili çeşitli konferans ve toplantıya davet edilen Cafer Bozkurt, ülkemizde ve yurtdışındaki mimarlık okullarında davetli konuşmacı ve öğretim görevlisi olarak da bulundu. Pek çok mimarlık ve kentleştirme yarışmasına jüri üyesi olarak görev almıştır. İstanbul Teknik Üniversitesi, Yıldız Teknik Üniversitesi, Bursa Uludağ Üniversitesi, Doğu Akdeniz Üniversitesi Mimarlık Bölümleri ile Erasmus Programı kapsamında İspanya'da Valencia Politécnica Üniversitesi'nde ve Norveç'te Stavanger Üniversitesi'nde konuk öğretim üyesi oldu.</p>
<p>İnsan ve toplum gereksinmeleri için, ulusal ve evrensel kültür bağlamında fikir ve çözümler üreten çeşitli dernek, sivil toplum örgütleri ve kamu kurumlarında üyelik yapan Bozkurt, mimarlık mesleğinin toplumsal kabulü ve saygınlığını arttırılması amacı ile çalışmaktadır. Bozkurt mesleki etkinliğini kendi sorumluluğu altında ve bağımsız olarak sürdüren mimarlar tarafından 1987'de Ankara'da kurulan Türk Serbest Mimarlar Derneği'ne 2000-2004 yılları arasında başkanlık yaptı. 2003 yılında ise aynı amaçla kurulan İstanbul Serbest Mimarlar Derneği'nin kurucu üyelerinden biri oldu ve 2005-2007 yılları arasında bu derneğe de başkanlık yaptı. Aynı zamanda 1996 yılında Mimarlık Vakfı'nın da kurucu üyesi oldu.</p>
<p>Bunu takiben 2008-2011 arasında Türkiye Cumhuriyeti Kültür Bakanlığı'na bağlı 4 Nolu Kültür ve Tabiat Varlıklarını Koruma Yüksek Kurulu'na üyelik ve başkanlık yaptı. İstanbul'da Tarihi Yarımada'da bulunan kültür ve tabiat varlıklarının restorasyonu ve korunmasından sorumlu olan bu kurulun en önemli şehircilik, ulaşım ve kültür projelerinde karar verici rol oynadı. Görev süresi dolduktan sonra, 2011-2013 yılları arasında Boğaziçi'nden sorumlu 3 Nolu Kültür Varlıklarını Koruma Yüksek Kurulu'na üyelik ve başkanlık yaptı.</p>
HTML;
    }

    public static function defaultStudioBody(): string
    {
        return <<<'HTML'
<p>1971 yılında Cafer Bozkurt tarafından İstanbul'da kurulmuş olan Cafer Bozkurt Mimarlık, evrensel tasarım anlayışı ve uluslararası standartlarda iş üretmekle beraber kentleri, çevrelerini, içinde bulunduğu coğrafi koşulları ve yerel kültürü dikkate alarak yorumlamakta; tasarım ilkelerini CBA kimliği, çağdaş yaşamın gereklilikleri ve gerçekleştiği ortamın kültürel bağlamı arasında yaşanan diyalogların bir sentezinde konumlandırmaktadır. Günümüz modernizminin dünyadaki gelişimini kendi kültürümüzün yaşamının şekillendirdiği bu kaynakların geçmişi, bugünü ve geleceğini gözeterek, insan yaşamını daha sağlıklı, sürdürülebilir ve adil bir geleceğe doğru götürecek bir meslek olarak görmektedir.</p>
<p>CBA, yurtiçinde ve yurt dışında, kapsamlı çevre düzenlemesi bütünlüğü içerisinde her ölçekte kentsel, çevresel, mimari, müstakil konutlara kadar uzanan, çok farklı ölçekte ve içerikli mimarlık pratiği içinde yer almıştır.</p>
<p>CBA tasarım ve uygulama kapsamı içindeki:</p>
<ul>
<li>Tarihi Mekanda Çağdaş Yapı Tasarımı: Tarihi Yarımada'da çeşitli restorasyon ve tasarım projeleri,</li>
<li>Boğaziçi kıyısındaki yalı ve köşk restorasyonları,</li>
<li>Kamusal Mimari: Opera, konser salonları, tiyatro gibi önemli kültür ve eğitim yapıları,</li>
<li>Nitelikli konut projeleri,</li>
<li>Turizm Mimarisi: Tatil köyü / yalı / oteller, golf kulüpleri, sosyal tesis projeleri,</li>
<li>Yarışmalar ve danışmanlık projeleri yer almaktadır.</li>
</ul>
<p><strong>İnsan Yaşamına Saygı ve İnsan Ölçeği ile Uyumluluk</strong><br>CBA insan, çevre, evrensellik ve biraradalığı, insanın yaşam biçimini esas alarak tasarımına yansıtmaktadır. Bu tasarım yaklaşımı her projenin en ince detayında, yaşanan ile gelişen süreçte yönlendiren etmen olmaktadır.</p>
<p><strong>Kültür Birikimi, Tarihi Bağlam ve Çağdaş Çevreyle Konumlanma</strong><br>CBA projelerinde içinde bulunduğu çevre ve kültürün hem çağdaş girdilerine hem de tarihi bağlamına ciddiyet ve evrensel etik değerlerle yaklaşılmaktadır. Her yapı, çok kültürlü bir kültürel bağlamda yer alan kurgusu ile tasarlanmakta ve değerlendirilmektedir. Bu farkındalığın üzerine kurgulanan mimari tasarım yaklaşımı ile benzersiz mekânlar ve sürdürülebilir çevresel üretim anlayışı hedeflenmektedir.</p>
<p><strong>Mimari Tasarım Sürecinin bir parçası olarak Üreticilerle Tasarım</strong><br>CBA mimari tasarımda, malzeme ölçeğindeki ölçütleri ve uzmanları bir araya getirerek evrensel bir yaklaşımla değerlendirir. Üretici firma önerileri ile özgün kurgulanmış sonucu tasarımın malzemeleştirilmesinde kaliteli bir mimari ürün sağlanmaktadır. Özellikle üretim tasarımında kalite ve estetiğe önem veren CBA, üreticilerle tasarımı bütüncül bir süreç olarak ele almakta; peyzajından detayına kadar düşünmektedir.</p>
<p><strong>Projeye Özgü Tasarım ve Malzemede İnovasyon</strong><br>CBA; farklı ölçü, iklim, işlev, fonksiyon ve bağlamında oluşan işlere her projenin kendine has tasarım fırsatı olarak bakmaktadır. Her proje, malzeme ve detayların özgün karakteriyle biçimlenen, kullanıcı ihtiyacına ve çevresel koşullara cevap veren, sürdürülebilir, geliştirilebilir ve uygulanabilir olmaktadır.</p>
HTML;
    }
}
