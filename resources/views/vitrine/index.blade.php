<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Academie Future Football Djerba</title>
<meta name="description" content="Academie Future Football Djerba : formation de football pour enfants et jeunes, discipline et plaisir de jouer.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
<style>
:root{
  --blue:#2f79b7; --navy:#142d5b; --gold:#d2af55; --ink:#17243b; --bg:#f5f8fc; --card:#ffffff; --muted:#586981;
  --radius:16px;
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Cairo',system-ui,sans-serif;color:var(--ink);background:var(--bg);line-height:1.6}
h1,h2,h3{font-family:'Oswald','Cairo',sans-serif;letter-spacing:.5px;line-height:1.15}
[dir="rtl"] h1,[dir="rtl"] h2,[dir="rtl"] h3{font-family:'Cairo',sans-serif;letter-spacing:0;font-weight:800}
a{color:inherit;text-decoration:none}
.container{width:min(1100px,92%);margin-inline:auto}

/* Header */
header{position:sticky;top:0;z-index:50;background:rgba(20,45,91,.96);backdrop-filter:blur(6px);color:#fff}
.nav{display:flex;align-items:center;justify-content:space-between;height:68px;gap:16px}
.logo{display:flex;align-items:center;gap:10px;font-family:'Oswald','Cairo',sans-serif;font-size:1.25rem;font-weight:700}
.logo-image{width:46px;height:46px;flex:none;object-fit:contain;border:2px solid var(--gold);border-radius:50%;background:#fff}
nav ul{display:flex;gap:22px;list-style:none;font-weight:600;font-size:.95rem}
nav a:hover{color:var(--gold)}
.lang{border:2px solid var(--gold);color:var(--gold);background:transparent;border-radius:999px;padding:4px 14px;font:inherit;font-weight:700;cursor:pointer}
.lang:hover{background:var(--gold);color:var(--navy)}
.menu-btn{display:none;background:none;border:0;color:#fff;font-size:1.7rem;cursor:pointer}
@media(max-width:820px){
  .menu-btn{display:block}
  nav{position:absolute;top:68px;inset-inline:0;background:var(--navy);display:none;padding:12px 4%}
  nav.open{display:block}
  nav ul{flex-direction:column;gap:14px;padding:10px 0}
}

/* Hero */
.hero{position:relative;color:#fff;padding:96px 0 110px;overflow:hidden;
  background:
   radial-gradient(circle at 85% 20%,rgba(210,175,85,.25),transparent 40%),
   repeating-linear-gradient(90deg,#2f79b7 0 70px,#1e4f88 70px 140px);
}
.hero::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(20,45,91,.55),rgba(20,45,91,.9))}
.hero .container{position:relative;z-index:1;max-width:820px}
.badge{display:inline-block;background:var(--gold);color:var(--navy);font-weight:800;border-radius:999px;padding:4px 16px;font-size:.85rem;margin-bottom:18px}
.hero h1{font-size:clamp(2.3rem,6vw,4.2rem);text-transform:uppercase}
[dir="rtl"] .hero h1{text-transform:none}
.hero p{margin:18px 0 30px;font-size:1.15rem;max-width:620px;opacity:.95}
.btns{display:flex;flex-wrap:wrap;gap:12px}
.btn{display:inline-block;padding:13px 28px;border-radius:999px;font-weight:800;border:2px solid transparent;cursor:pointer;font:inherit;font-weight:800;transition:.2s}
.btn-primary{background:var(--gold);color:var(--navy)}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.25)}
.btn-ghost{border-color:#fff;color:#fff}
.btn-ghost:hover{background:#fff;color:var(--navy)}

/* Stats */
.stats{margin-top:-48px;position:relative;z-index:2}
.stats .grid{background:var(--card);border-radius:var(--radius);box-shadow:0 12px 40px rgba(20,45,91,.15);display:grid;grid-template-columns:repeat(4,1fr);text-align:center;padding:26px 10px}
.stat b{display:block;font-family:'Oswald',sans-serif;font-size:2.2rem;color:var(--blue)}
.stat span{color:var(--muted);font-size:.92rem}
@media(max-width:700px){.stats .grid{grid-template-columns:repeat(2,1fr);gap:18px}}

section{padding:78px 0}
.title{text-align:center;margin-bottom:44px}
.title h2{font-size:clamp(1.8rem,4vw,2.6rem);color:var(--navy);text-transform:uppercase}
[dir="rtl"] .title h2{text-transform:none}
.title p{color:var(--muted);margin-top:8px}
.title::after{content:"";display:block;width:64px;height:5px;background:var(--gold);border-radius:3px;margin:14px auto 0}

/* About */
.about{display:grid;grid-template-columns:1.1fr 1fr;gap:40px;align-items:center}
.about p{margin-bottom:14px;color:#263b58}
.values{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.value{background:var(--card);border-radius:var(--radius);padding:20px;box-shadow:0 4px 16px rgba(20,45,91,.08)}
.value .ico{font-size:1.8rem}
.value h3{font-size:1.05rem;margin:6px 0 4px;color:var(--blue)}
.value p{font-size:.88rem;color:var(--muted);margin:0}
@media(max-width:820px){.about{grid-template-columns:1fr}}

/* Programs */
.programs{background:#eaf3fb}
.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:22px}
.card{background:var(--card);border-radius:var(--radius);padding:26px;box-shadow:0 6px 22px rgba(20,45,91,.1);border-top:6px solid var(--gold);transition:.25s}
.card:hover{transform:translateY(-6px)}
.card .age{font-family:'Oswald',sans-serif;font-size:1.9rem;color:var(--blue)}
.card h3{font-size:1.1rem;margin-bottom:8px}
.card p{color:var(--muted);font-size:.93rem}
.card .meta{margin-top:14px;font-size:.88rem;font-weight:700;color:var(--navy);border-top:1px dashed #d5e1ef;padding-top:12px}

/* Coaches */
.coach{text-align:center}
.avatar{width:96px;height:96px;border-radius:50%;margin:0 auto 14px;background:linear-gradient(135deg,var(--blue),var(--navy));color:var(--gold);display:grid;place-items:center;font-size:2.2rem;font-weight:800;font-family:'Oswald',sans-serif}

/* Gallery carousel */
.gallery-carousel{position:relative}
.gallery{display:flex;gap:18px;overflow-x:auto;scroll-snap-type:x mandatory;scroll-behavior:smooth;overscroll-behavior-inline:contain;padding:4px 2px 16px;scrollbar-width:thin;scrollbar-color:var(--blue) #dce8f3}
.gallery-item{flex:0 0 calc((100% - 36px)/3);min-width:0;overflow:hidden;scroll-snap-align:start;border-radius:var(--radius);background:var(--card);box-shadow:0 6px 22px rgba(20,45,91,.1)}
.gallery-item img{display:block;width:100%;aspect-ratio:4/3;object-fit:cover}
.gallery-item figcaption{padding:12px 16px;color:var(--navy);font-weight:700}
.gallery-empty{flex:0 0 100%;padding:28px;border:2px dashed #a9c7e0;border-radius:var(--radius);color:var(--muted);text-align:center}
.gallery-controls{display:flex;justify-content:center;gap:12px;margin-top:12px}
.gallery-control{width:44px;height:44px;border:0;border-radius:50%;background:var(--navy);color:#fff;font-size:1.3rem;cursor:pointer;transition:background .2s,transform .2s}
.gallery-control:hover{background:var(--blue);transform:translateY(-2px)}
.gallery-control:focus-visible{outline:3px solid var(--gold);outline-offset:3px}
@media(max-width:820px){.gallery-item{flex-basis:calc((100% - 18px)/2)}}
@media(max-width:560px){.gallery-item{flex-basis:100%}}
.ph{aspect-ratio:4/3;border-radius:var(--radius);background:linear-gradient(135deg,#2f79b7,#57a9d8);display:grid;place-items:center;color:rgba(255,255,255,.85);font-weight:700;text-align:center;padding:10px;border:2px dashed rgba(210,175,85,.6)}
.note{text-align:center;color:var(--muted);font-size:.88rem;margin-top:16px}

/* Registration */
.register{background:var(--navy);color:#fff}
.register .title h2{color:#fff}
.register .title p{color:#dceaf7}
form{background:#fff;color:var(--ink);border-radius:var(--radius);padding:30px;max-width:760px;margin:auto;display:grid;grid-template-columns:1fr 1fr;gap:16px;box-shadow:0 16px 50px rgba(0,0,0,.3)}
form .full{grid-column:1/-1}
label{font-weight:700;font-size:.9rem;display:block;margin-bottom:5px}
input,select,textarea{width:100%;padding:12px 14px;border:2px solid #d8e3f0;border-radius:10px;font:inherit;background:#fbfdfb}
input:focus,select:focus,textarea:focus{outline:none;border-color:var(--blue)}
.check{display:flex;gap:10px;align-items:flex-start;font-size:.88rem;color:var(--muted)}
.check input{width:auto;margin-top:5px}
form button{grid-column:1/-1}
#msg{grid-column:1/-1;font-weight:700;color:var(--blue);display:none;text-align:center}
@media(max-width:620px){form{grid-template-columns:1fr}}

/* Contact */
.contact{display:grid;grid-template-columns:1fr 1fr;gap:30px}
.info li{list-style:none;display:flex;gap:12px;margin-bottom:16px;align-items:flex-start}
.info .ico{font-size:1.4rem}
.map{border-radius:var(--radius);min-height:300px;overflow:hidden;border:2px solid #a9c7e0;box-shadow:0 4px 16px rgba(20,45,91,.08)}
.map iframe{width:100%;height:100%;min-height:300px;border:0}
@media(max-width:820px){.contact{grid-template-columns:1fr}}

footer{background:#0b1d3e;color:#d2dfed;text-align:center;padding:26px 0;font-size:.9rem}

/* WhatsApp */
.wa{position:fixed;bottom:20px;inset-inline-end:20px;z-index:60;background:#25d366;color:#fff;border-radius:999px;padding:13px 20px;font-weight:800;box-shadow:0 8px 24px rgba(0,0,0,.3)}
.wa:hover{transform:scale(1.05)}
</style>
</head>
<body>

<header>
  <div class="container nav">
    <a href="#top" class="logo"><img class="logo-image" src="/images/logo.jpg" alt=""><span data-i18n="brand">Academie Future Football Djerba</span></a>
    <button class="menu-btn" id="menuBtn" aria-label="Menu">☰</button>
    <nav id="nav">
      <ul>
        <li><a href="#about" data-i18n="nav_about">L'académie</a></li>
        <li><a href="#programs" data-i18n="nav_programs">Programmes</a></li>
        <li><a href="#coaches" data-i18n="nav_coaches">Coachs</a></li>
        <li><a href="#gallery" data-i18n="nav_gallery">Galerie</a></li>
        <li><a href="#contact" data-i18n="nav_contact">Contact</a></li>
        <li><a href="/login" data-i18n="nav_login">Espace parents</a></li>
      </ul>
    </nav>
    <button class="lang" id="langBtn">العربية</button>
  </div>
</header>

<main id="top">
<section class="hero">
  <div class="container">
    <span class="badge" data-i18n="hero_badge">Inscriptions ouvertes · Saison 2026/2027</span>
    <h1 data-i18n="hero_title">Là où naissent les futurs champions</h1>
    <p data-i18n="hero_text">Une formation sérieuse, un encadrement diplômé et beaucoup de plaisir pour les enfants de 6 à 17 ans. Venez essayer gratuitement une séance.</p>
    <div class="btns">
      <a href="#register" class="btn btn-primary" data-i18n="hero_cta1">Réserver un essai gratuit</a>
      <a href="#programs" class="btn btn-ghost" data-i18n="hero_cta2">Voir les programmes</a>
    </div>
  </div>
</section>

<div class="container stats">
  <div class="grid">
    <div class="stat"><b>{{ $groups->isNotEmpty() ? $groups->min('age_min').'–'.$groups->max('age_max') : '—' }}</b><span data-i18n="stat1">ans</span></div>
    <div class="stat"><b>{{ $categoryCount }}</b><span data-i18n="stat2">catégories d'âge</span></div>
    <div class="stat"><b>{{ $coaches->count() }}</b><span data-i18n="stat3">coachs actifs</span></div>
    <div class="stat"><b>{{ $playerCount }}</b><span data-i18n="stat4">joueurs inscrits</span></div>
  </div>
</div>

<section id="about">
  <div class="container">
    <div class="title"><h2 data-i18n="about_h">Notre académie</h2><p data-i18n="about_sub">Former des joueurs, mais aussi des personnes</p></div>
    <div class="about">
      <div>
        <p data-i18n="about_p1">Fondée par des passionnés de football, l'académie accompagne chaque enfant selon son âge et son niveau, du premier ballon jusqu'à la compétition.</p>
        <p data-i18n="about_p2">Notre méthode associe technique, condition physique, esprit d'équipe et respect. Chaque joueur progresse à son rythme dans un cadre sécurisé et bienveillant.</p>
      </div>
      <div class="values">
        <div class="value"><div class="ico">⚽</div><h3 data-i18n="v1_h">Technique</h3><p data-i18n="v1_p">Maîtrise du ballon dès le plus jeune âge.</p></div>
        <div class="value"><div class="ico">🤝</div><h3 data-i18n="v2_h">Esprit d'équipe</h3><p data-i18n="v2_p">Jouer ensemble, gagner ensemble.</p></div>
        <div class="value"><div class="ico">🎓</div><h3 data-i18n="v3_h">Discipline</h3><p data-i18n="v3_p">Respect, ponctualité et sérieux.</p></div>
        <div class="value"><div class="ico">🛡️</div><h3 data-i18n="v4_h">Sécurité</h3><p data-i18n="v4_p">Encadrement attentif et terrain adapté.</p></div>
      </div>
    </div>
  </div>
</section>

<section id="programs" class="programs">
  <div class="container">
    <div class="title"><h2 data-i18n="prog_h">Nos programmes</h2><p data-i18n="prog_sub">Un groupe adapté à chaque âge</p></div>
    <div class="cards">
      @forelse ($groups as $group)
        <article class="card">
          <div class="age">{{ $group->category }}</div>
          <h3>{{ $group->name }}</h3>
          <p>{{ $group->age_min }}–{{ $group->age_max }} <span data-i18n="years">ans</span></p>
          <div class="meta">
            @if ($group->schedule) {{ $group->schedule }} @else <span data-i18n="schedule_tbd">Horaires à définir</span> @endif
            @if ($group->location)<br><span data-i18n="location_label">Lieu</span> : {{ $group->location }}@endif
            <br><span data-i18n="program_price">Tarif mensuel</span> : <bdi>{{ number_format((float) $group->price, 3, ',', ' ') }} DT / mois</bdi>
          </div>
        </article>
      @empty
        <div class="gallery-empty" data-i18n="programs_empty">Les programmes seront bientôt disponibles.</div>
      @endforelse
    </div>
  </div>
</section>

<section id="coaches">
  <div class="container">
    <div class="title"><h2 data-i18n="coach_h">Notre équipe</h2><p data-i18n="coach_sub">Des éducateurs diplômés et passionnés</p></div>
    <div class="cards">
      @forelse ($coaches as $coach)
        <article class="card coach">
          <div class="avatar">{{ mb_strtoupper(mb_substr($coach->name, 0, 1)) }}</div>
          <h3>{{ $coach->name }}</h3>
          @if ($coach->coachedGroups->isNotEmpty())
            <p>{{ $coach->coachedGroups->pluck('category')->unique()->implode(' · ') }}</p>
          @else
            <p data-i18n="coach_no_groups">Équipe technique</p>
          @endif
        </article>
      @empty
        <div class="gallery-empty" data-i18n="team_empty">L’équipe sera bientôt présentée.</div>
      @endforelse
    </div>
  </div>
</section>

<section id="gallery" class="programs">
  <div class="container">
    <div class="title"><h2 data-i18n="gal_h">Galerie</h2><p data-i18n="gal_sub">La vie de l'académie</p></div>
    <div class="gallery-carousel">
      <div class="gallery" id="galleryTrack" aria-label="Photos de la vie de l’académie">
      @forelse ($galleryItems as $item)
        <figure class="gallery-item">
          <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption ?: 'Photo de la vie de l’académie' }}" loading="lazy">
          @if ($item->caption)<figcaption>{{ $item->caption }}</figcaption>@endif
        </figure>
      @empty
        <div class="gallery-empty" data-i18n="gallery_empty">Les photos de l’académie seront bientôt disponibles.</div>
      @endforelse
      </div>
      @if ($galleryItems->count() > 1)
        <div class="gallery-controls" aria-label="Commandes du carrousel">
          <button class="gallery-control" id="galleryPrev" type="button" aria-label="Photo précédente">‹</button>
          <button class="gallery-control" id="galleryNext" type="button" aria-label="Photo suivante">›</button>
        </div>
      @endif
    </div>
    <p class="note" data-i18n="gallery_privacy">Les photos de mineurs sont publiées uniquement avec l’autorisation de leurs représentants légaux.</p>
  </div>
</section>

<section id="contact">
  <div class="container">
    <div class="title"><h2 data-i18n="ct_h">Contact</h2><p data-i18n="ct_sub">Nous sommes à votre écoute</p></div>
    <div class="contact">
      <ul class="info">
        <li><span class="ico">📍</span><div><b data-i18n="ct_addr_l">Adresse</b><br><span data-i18n="ct_addr">Oued Amghar, Djerba, Tunisia, 4175</span></div></li>
        <li><span class="ico">📞</span><div><b data-i18n="ct_tel_l">Téléphone</b><br><a href="tel:+21650710709" dir="ltr">+216 50 710 709</a></div></li>
        <li><span class="ico">✉️</span><div><b>E-mail</b><br><a href="mailto:contact@votreacademie.tn">contact@votreacademie.tn</a></div></li>
        <li><span class="ico">🕒</span><div><b data-i18n="ct_h_l">Horaires du bureau</b><br><span data-i18n="ct_hours">Lun–Sam, 15h00–20h00</span></div></li>
        <li><span class="ico">📱</span><div><b data-i18n="ct_soc">Réseaux</b><br><a href="https://www.facebook.com/profile.php?id=61577764713440">Facebook</a> · <a href="https://www.instagram.com/future_football_djerba?utm_source=ig_web_button_share_sheet&vrfl=ZDNlZDc0MzIxNw==">Instagram</a></div></li>
      </ul>
      
      <!-- Carte intégrée ici -->
      <div class="map">
        <iframe 
          loading="lazy" 
          allowfullscreen 
          src="https://maps.google.com/maps?q=Oued%20Amghar%2C%20Djerba%2C%20Tunisia&t=&z=14&ie=UTF8&iwloc=&output=embed">
        </iframe>
      </div>

    </div>
  </div>
</section>
</main>

<a class="wa" href="https://wa.me/21650710709" target="_blank" rel="noopener">💬 WhatsApp</a>

<footer>
  <div class="container">© <span id="yr"></span> <span data-i18n="brand_full">Academie Future Football Djerba</span> — <span data-i18n="rights">Tous droits réservés</span></div>
</footer>

<script>
const T = {
 fr:{brand:"Academie Future Football Djerba",nav_about:"L'académie",nav_programs:"Programmes",nav_coaches:"Coachs",nav_gallery:"Galerie",nav_register:"Inscription",nav_contact:"Contact",nav_login:"Espace parents",
 hero_badge:"Inscriptions ouvertes · Saison 2026/2027",hero_title:"Là où naissent les futurs champions",hero_text:"Une formation sérieuse, un encadrement diplômé et beaucoup de plaisir pour les enfants de 6 à 17 ans. Venez essayer gratuitement une séance.",hero_cta1:"Réserver un essai gratuit",hero_cta2:"Voir les programmes",
 stat1:"ans",stat2:"catégories d'âge",stat3:"coachs actifs",stat4:"joueurs inscrits",
 about_h:"Notre académie",about_sub:"Former des joueurs, mais aussi des personnes",about_p1:"Fondée par des passionnés de football, l'académie accompagne chaque enfant selon son âge et son niveau, du premier ballon jusqu'à la compétition.",about_p2:"Notre méthode associe technique, condition physique, esprit d'équipe et respect. Chaque joueur progresse à son rythme dans un cadre sécurisé et bienveillant.",
 v1_h:"Technique",v1_p:"Maîtrise du ballon dès le plus jeune âge.",v2_h:"Esprit d'équipe",v2_p:"Jouer ensemble, gagner ensemble.",v3_h:"Discipline",v3_p:"Respect, ponctualité et sérieux.",v4_p:"Sécurité",v4_p:"Encadrement attentif et terrain adapté.",
 prog_h:"Nos programmes",prog_sub:"Un groupe adapté à chaque âge",years:"ans",schedule_tbd:"Horaires à définir",location_label:"Lieu",program_price:"Tarif mensuel",programs_empty:"Les programmes seront bientôt disponibles.",
 p1_h:"Découverte",p1_p:"6–7 ans : jeux avec ballon, coordination et plaisir avant tout.",p1_m:"Mercredi & samedi · 16h00",
 p2_h:"Initiation",p2_p:"8–9 ans : bases techniques, passes, conduite de balle, petits matchs.",p2_m:"Mercredi & samedi · 16h00",
 p3_h:"Apprentissage",p3_p:"10–11 ans : tactique de base, jeu collectif et premiers tournois.",p3_m:"Mardi, jeudi & samedi · 17h00",
 p4_h:"Développement",p4_p:"12–13 ans : intensité, vitesse de jeu et lecture du jeu.",p4_m:"Mardi, jeudi & samedi · 17h30",
 p5_h:"Perfectionnement",p5_p:"14–15 ans : préparation physique et compétition.",p5_m:"Lundi, mercredi & vendredi · 18h00",
 p6_h:"Performance",p6_p:"16–17 ans : haut niveau, détection et préparation aux tests.",p6_m:"Lundi, mercredi & vendredi · 19h00",
 prog_note:"Horaires indicatifs, à confirmer. Tarifs communiqués lors de l'essai gratuit.",
 coach_h:"Notre équipe",coach_sub:"Des éducateurs diplômés et passionnés",coach_no_groups:"Équipe technique",team_empty:"L’équipe sera bientôt présentée.",
 gal_h:"Galerie",gal_sub:"La vie de l'académie",gallery_empty:"Les photos de l’académie seront bientôt disponibles.",gallery_privacy:"Les photos de mineurs sont publiées uniquement avec l’autorisation de leurs représentants légaux.",
 reg_h:"Inscription / Essai gratuit",reg_sub:"Remplissez le formulaire, nous vous rappelons rapidement",f_parent:"Nom du parent",f_phone:"Téléphone",f_child:"Nom de l'enfant",f_birth:"Date de naissance",f_email:"E-mail (facultatif)",f_msg:"Message (niveau, club précédent...)",f_consent:"Je suis le représentant légal de l'enfant et j'accepte que ces informations soient utilisées pour traiter l'inscription.",f_send:"Envoyer ma demande",f_ok:"Merci ! Votre demande a bien été envoyée.",
 ct_h:"Contact",ct_sub:"Nous sommes à votre écoute",ct_addr_l:"Adresse",ct_addr:"Oued Amghar, Djerba, Tunisia, 4175",ct_tel_l:"Téléphone",ct_h_l:"Horaires du bureau",ct_hours:"Lun–Sam, 15h00–20h00",ct_soc:"Réseaux",
 brand_full:"Academie Future Football Djerba",rights:"Tous droits réservés"},
 ar:{brand:"أكاديمية دجربة لكرة القدم المستقبلية",nav_about:"الأكاديمية",nav_programs:"البرامج",nav_coaches:"المدربون",nav_gallery:"المعرض",nav_register:"التسجيل",nav_contact:"اتصل بنا",nav_login:"فضاء الأولياء",
 hero_badge:"التسجيل مفتوح · موسم 2026/2027",hero_title:"هنا يولد أبطال المستقبل",hero_text:"تكوين جدّي وإشراف من مدربين مختصين ومتعة كبيرة للأطفال من 6 إلى 17 سنة. جرّبوا حصة تدريبية مجانية.",hero_cta1:"احجز حصة تجريبية مجانية",hero_cta2:"اكتشف البرامج",
 stat1:"سنة",stat2:"فئات عمرية",stat3:"مدربون نشطون",stat4:"لاعب مسجّل",
 about_h:"أكاديميتنا",about_sub:"نكوّن لاعبين… وأشخاصًا أيضًا",about_p1:"أسّسها عشّاق كرة القدم، وترافق الأكاديمية كل طفل حسب سنّه ومستواه، من أول كرة إلى المنافسة.",about_p2:"تجمع طريقتنا بين المهارة واللياقة البدنية وروح الفريق والاحترام. يتقدّم كل لاعب بإيقاعه في إطار آمن ومشجّع.",
 v1_h:"المهارة",v1_p:"إتقان الكرة منذ الصغر.",v2_h:"روح الفريق",v2_p:"نلعب معًا ونفوز معًا.",v3_h:"الانضباط",v3_p:"الاحترام والالتزام بالمواعيد والجدّية.",v4_h:"الأمان",v4_p:"إشراف دقيق وملعب مناسب.",
 prog_h:"برامجنا",prog_sub:"مجموعة مناسبة لكل سنّ",years:"سنة",schedule_tbd:"المواعيد تحدد لاحقًا",location_label:"المكان",program_price:"السعر",programs_empty:"ستُعلن البرامج قريبًا.",
 p1_h:"الاكتشاف",p1_p:"6–7 سنوات: ألعاب بالكرة وتناسق حركي ومتعة أولًا.",p1_m:"الأربعاء والسبت · 16:00",
 p2_h:"التعلّم الأوّلي",p2_p:"8–9 سنوات: أساسيات المهارة والتمرير والمراوغة ومباريات صغيرة.",p2_m:"الأربعاء والسبت · 16:00",
 p3_h:"التكوين",p3_p:"10–11 سنة: أساسيات التكتيك واللعب الجماعي وأولى البطولات.",p3_m:"الثلاثاء والخميس والسبت · 17:00",
 p4_h:"التطوير",p4_p:"12–13 سنة: الشدّة وسرعة اللعب وقراءة المباراة.",p4_m:"الثلاثاء والخميس والسبت · 17:30",
 p5_h:"التحسين",p5_p:"14–15 سنة: إعداد بدني ومنافسة.",p5_m:"الإثنين والأربعاء والجمعة · 18:00",
 p6_h:"الأداء",p6_p:"16–17 سنة: مستوى عالٍ واكتشاف المواهب والتحضير للاختبارات.",p6_m:"الإثنين والأربعاء والجمعة · 19:00",
 prog_note:"المواعيد إرشادية وقابلة للتأكيد. تُقدَّم الأسعار خلال الحصة التجريبية.",
 coach_h:"فريقنا",coach_sub:"مربّون مختصون وشغوفون",coach_no_groups:"الفريق الفني",team_empty:"سيتم تقديم الفريق قريبًا.",
 gal_h:"المعرض",gal_sub:"حياة الأكاديمية",gallery_empty:"ستُضاف صور الأكاديمية قريبًا.",gallery_privacy:"لا تُنشر صور القاصرين إلا بموافقة أوليائهم القانونيين.",
 reg_h:"التسجيل / حصة تجريبية مجانية",reg_sub:"املأ الاستمارة وسنتصل بك قريبًا",f_parent:"اسم الولي",f_phone:"الهاتف",f_child:"اسم الطفل",f_birth:"تاريخ الولادة",f_email:"البريد الإلكتروني (اختياري)",f_msg:"رسالة (المستوى، النادي السابق...)",f_consent:"أنا الولي الشرعي للطفل وأوافق على استعمال هذه المعلومات لمعالجة التسجيل.",f_send:"إرسال الطلب",f_ok:"شكرًا! تم إرسال طلبك بنجاح.",
 ct_h:"اتصل بنا",ct_sub:"نحن في خدمتكم",ct_addr_l:"العنوان",ct_addr:"Oued Amghar, Djerba, Tunisia, 4175",ct_tel_l:"الهاتف",ct_h_l:"أوقات المكتب",ct_hours:"من الإثنين إلى السبت، 15:00–20:00",ct_soc:"مواقع التواصل",
 brand_full:"أكاديمية دجربة لكرة القدم المستقبلية",rights:"جميع الحقوق محفوظة"}
};
function setLang(l){
  document.documentElement.lang=l;
  document.documentElement.dir=l==='ar'?'rtl':'ltr';
  document.querySelectorAll('[data-i18n]').forEach(e=>{const k=e.dataset.i18n;if(T[l][k])e.textContent=T[l][k]});
  document.getElementById('langBtn').textContent=l==='ar'?'Français':'العربية';
  try{localStorage.setItem('lang',l)}catch(e){}
}
let lang='fr';
try{lang=localStorage.getItem('lang')||'fr'}catch(e){}
setLang(lang);
document.getElementById('langBtn').onclick=()=>{lang=lang==='fr'?'ar':'fr';setLang(lang)};
document.getElementById('menuBtn').onclick=()=>document.getElementById('nav').classList.toggle('open');
document.querySelectorAll('nav a').forEach(a=>a.onclick=()=>document.getElementById('nav').classList.remove('open'));
document.getElementById('yr').textContent=new Date().getFullYear();

const galleryTrack=document.getElementById('galleryTrack');
if(galleryTrack){
  const moveGallery=direction=>{
    const item=galleryTrack.querySelector('.gallery-item');
    if(!item)return;
    const step=item.getBoundingClientRect().width+parseFloat(getComputedStyle(galleryTrack).gap||0);
    const rtl=document.documentElement.dir==='rtl';
    galleryTrack.scrollBy({left:direction*step*(rtl?-1:1),behavior:'smooth'});
  };
  document.getElementById('galleryPrev')?.addEventListener('click',()=>moveGallery(-1));
  document.getElementById('galleryNext')?.addEventListener('click',()=>moveGallery(1));
}

// Envoi du formulaire sans quitter la page
if(document.getElementById('regForm')){
  document.getElementById('regForm').addEventListener('submit',async e=>{
    e.preventDefault();
    const f=e.target, m=document.getElementById('msg');
    try{
      const r=await fetch(f.action,{method:'POST',body:new FormData(f),headers:{'Accept':'application/json'}});
      if(r.ok){m.style.display='block';f.reset()}else{alert('Erreur / خطأ')}
    }catch(err){alert('Erreur / خطأ')}
  });
}
</script>
</body>
</html>
