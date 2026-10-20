/* Traductions FR / AR et bascule RTL.
   Usage : <span data-i18n="cle">Texte FR</span>
           <input data-i18n-placeholder="cle">
   Un bouton #langBtn bascule la langue ; le choix est mémorisé. */
const T = {
  fr: {
    brand: "Academie Future Football Djerba", logo: "", langBtn: "العربية",
    /* connexion */
    login_space: "Espace parents", login_sub: "Suivez vos enfants, inscrivez-les et payez en ligne.",
    tab_login: "Connexion", tab_signup: "Créer un compte",
    f_id: "Téléphone ou e-mail", f_pass: "Mot de passe", f_name: "Nom complet", f_pass2: "Confirmer le mot de passe",
    forgot: "Mot de passe oublié ?", btn_login: "Se connecter", btn_signup: "Créer mon compte",
    help: "Besoin d'aide ?", wa: "Écrire sur WhatsApp",
    /* accueil parent */
    hi: "Bonjour, Sonia", hi_sub: "Voici le suivi de vos enfants.",
    due: "Paiement à régler", due_for: "Youssef · saison 2026/2027", pay: "Payer", price: "[PRIX] DT",
    my_kids: "Mes enfants",
    k1_meta: "U11 · Mar, Jeu & Sam · 17h00", k1_s: "Inscription validée",
    k2_meta: "U9 · Mer & Sam · 16h00", k2_s: "En attente de validation",
    add_kid: "Inscrire un enfant", next: "Prochaine séance", next_v: "Mardi · 17h00",
    next_p: "Stade municipal · Coach Mehdi",
    ann: "Annonce du club", ann_v: "Séance de samedi maintenue. Prévoir une gourde et des protège-tibias.",
    nav_home: "Accueil", nav_kids: "Enfants", nav_pays: "Paiements", nav_me: "Profil",
    /* inscription / paiement */
    enr_title: "Inscrire un enfant", enr_step: "Étape 2 sur 3 · Choisir le groupe", back: "Retour",
    kid_age: "9 ans · catégorie conseillée U9", pick: "Groupe d'entraînement",
    g1: "U9 · Initiation", g1_w: "Mer & Sam · 16h00",
    g2: "U11 · Apprentissage", g2_w: "Mar, Jeu & Sam · 17h00",
    g3: "U13 · Développement", g3_w: "Mar, Jeu & Sam · 17h30",
    sum: "Récapitulatif", fee: "Frais d'inscription", kit: "Équipement (optionnel)", total: "Total", total_v: "[TOTAL] DT",
    photo: "J'autorise l'académie à photographier mon enfant lors des activités (facultatif).",
    secure: "Vous serez redirigé vers la page de paiement sécurisée de notre prestataire. L'académie ne conserve aucune donnée bancaire.",
    pay_online: "Payer en ligne", pay_later: "Payer plus tard ou sur place",
    /* présence */
    att_title: "Feuille de présence", coach: "Coach Mehdi", here: "présents",
    session: "U11 · Mardi · 17h00 · Stade municipal", att_hint: "Touchez un joueur pour changer son statut.",
    present: "Présent", absent: "Absent", save_att: "Enregistrer l'appel", saved: "Appel enregistré.",
    p1: "Youssef B.", p2: "Amine K.", p3: "Yassine M.", p4: "Seif T.", p5: "Nizar H.", p6: "Rami S.",
    nav_groups: "Mes groupes", nav_call: "Appel", nav_news: "Annonces",
    /* admin */
    a_n1: "Tableau de bord", a_n2: "Inscriptions", a_n3: "Joueurs", a_n4: "Groupes", a_n5: "Entraîneurs", a_n6: "Paiements",
    a_title: "Tableau de bord", a_sample: "Données d'exemple pour la maquette",
    a_export: "Exporter vers Excel", a_new: "Nouveau groupe",
    kp1: "Inscriptions à valider", kp2: "Joueurs actifs", kp3: "Paiements en attente", kp4: "Présence moyenne",
    a_table: "Inscriptions à valider", c_kid: "Enfant", c_parent: "Parent", c_group: "Groupe", c_pay: "Paiement", c_act: "Actions",
    paid: "Payé", wait: "En attente", ok: "Valider", no: "Refuser",
    a_groups: "Groupes et entraîneurs", coach_sami: "Coach Sami", players: "[N] joueurs",
    r1: "Lina", r1p: "Sonia B.", r1g: "U9 · Mer & Sam",
    r2: "Adam", r2p: "Karim T.", r2g: "U11 · Mar, Jeu & Sam",
    r3: "Ines", r3p: "Amel R.", r3g: "U13 · Mar, Jeu & Sam",
    r4: "Walid", r4p: "Hichem M.", r4g: "U15 · Lun, Mer & Ven",
    /* ajouts Laravel */
    hello: "Bonjour", logout: "Quitter", rej: "Inscription refusée", noreg: "Pas encore inscrit", register_now: "Inscrire",
    no_kids: "Aucun enfant pour le moment.", kid_new: "Ajouter un enfant", kid_edit: "Modifier la fiche",
    f_first: "Prénom", f_last: "Nom", f_birth: "Date de naissance", f_medical: "Informations médicales utiles (allergies, asthme…)",
    f_photo: "J'autorise la publication de photos de mon enfant", save: "Enregistrer",
    f_email_req: "E-mail", f_remember: "Se souvenir de moi", no_account: "Pas encore de compte ?", have_account: "Déjà un compte ?",
    pend_title: "Paiement sur place ou par virement",
    pend_text: "Le paiement en ligne n'est pas encore activé. Réglez le montant à l'académie ou par virement : l'administrateur confirmera votre paiement.",
    pend_amount: "Montant à régler", ret_title: "Statut du paiement", st_paid: "Paiement reçu. Merci !",
    st_pending: "Paiement en attente de confirmation.", st_failed: "Le paiement a échoué.", retry: "Réessayer", go_home: "Retour à l'accueil",
    cg_title: "Mes groupes", add_session: "Ajouter une séance", date_time: "Date et heure", no_sessions: "Aucune séance prévue.",
    open_call: "Faire l'appel", no_groups: "Aucun groupe ne vous est attribué.",
    session_history_link: "Consulter toutes les séances et l'historique", session_history: "Séances et historique",
    session_history_sub: "Retrouvez les séances et les appels par période.", history_year: "Année", history_month: "Mois",
    history_all_months: "Toute l'année", history_group: "Groupe", history_all_groups: "Tous les groupes",
    history_filter: "Filtrer les séances", history_call_done: "Appel fait", history_call_todo: "Appel à faire",
    history_call_edit: "Consulter ou modifier l'appel", history_empty: "Aucune séance trouvée pour cette période.",
    history_no_location: "Lieu non précisé"
  },
  ar: {
    brand: "أكاديمية دجربة لكرة القدم المستقبلية", logo: "", langBtn: "Français",
    login_space: "فضاء الأولياء", login_sub: "تابعوا أبناءكم وسجّلوهم وادفعوا عبر الإنترنت.",
    tab_login: "الدخول", tab_signup: "إنشاء حساب",
    f_id: "الهاتف أو البريد الإلكتروني", f_pass: "كلمة العبور", f_name: "الاسم الكامل", f_pass2: "تأكيد كلمة العبور",
    forgot: "نسيت كلمة العبور؟", btn_login: "تسجيل الدخول", btn_signup: "إنشاء حسابي",
    help: "تحتاج مساعدة؟", wa: "راسلنا على واتساب",
    hi: "مرحبًا، سنية", hi_sub: "إليكم متابعة أبنائكم.",
    due: "دفعة مستحقة", due_for: "يوسف · موسم 2026/2027", pay: "ادفع", price: "[السعر] د.ت",
    my_kids: "أبنائي",
    k1_meta: "U11 · الثلاثاء والخميس والسبت · 17:00", k1_s: "التسجيل مقبول",
    k2_meta: "U9 · الأربعاء والسبت · 16:00", k2_s: "في انتظار القبول",
    add_kid: "تسجيل طفل", next: "الحصة القادمة", next_v: "الثلاثاء · 17:00",
    next_p: "الملعب البلدي · المدرب مهدي",
    ann: "إعلان من النادي", ann_v: "حصة السبت مؤكدة. يرجى إحضار قارورة ماء وواقيات الساق.",
    nav_home: "الرئيسية", nav_kids: "الأطفال", nav_pays: "الدفوعات", nav_me: "الحساب",
    enr_title: "تسجيل طفل", enr_step: "الخطوة 2 من 3 · اختيار المجموعة", back: "رجوع",
    kid_age: "9 سنوات · الفئة المقترحة U9", pick: "مجموعة التدريب",
    g1: "U9 · التعلّم الأوّلي", g1_w: "الأربعاء والسبت · 16:00",
    g2: "U11 · التكوين", g2_w: "الثلاثاء والخميس والسبت · 17:00",
    g3: "U13 · التطوير", g3_w: "الثلاثاء والخميس والسبت · 17:30",
    sum: "الملخّص", fee: "معلوم التسجيل", kit: "التجهيزات (اختياري)", total: "المجموع", total_v: "[المجموع] د.ت",
    photo: "أسمح للأكاديمية بتصوير طفلي خلال الأنشطة (اختياري).",
    secure: "سيتم توجيهك إلى صفحة الدفع الآمنة لدى مزوّد الخدمة. لا تحتفظ الأكاديمية بأي معطيات بنكية.",
    pay_online: "ادفع عبر الإنترنت", pay_later: "الدفع لاحقًا أو في عين المكان",
    att_title: "ورقة الحضور", coach: "المدرب مهدي", here: "حاضرون",
    session: "U11 · الثلاثاء · 17:00 · الملعب البلدي", att_hint: "المس اللاعب لتغيير حالته.",
    present: "حاضر", absent: "غائب", save_att: "حفظ الحضور", saved: "تم حفظ الحضور.",
    p1: "يوسف ب.", p2: "أمين ك.", p3: "ياسين م.", p4: "سيف ط.", p5: "نزار ه.", p6: "رامي س.",
    nav_groups: "مجموعاتي", nav_call: "الحضور", nav_news: "الإعلانات",
    a_n1: "لوحة القيادة", a_n2: "التسجيلات", a_n3: "اللاعبون", a_n4: "المجموعات", a_n5: "المدربون", a_n6: "الدفوعات",
    a_title: "لوحة القيادة", a_sample: "معطيات تجريبية للمعاينة فقط",
    a_export: "تصدير إلى Excel", a_new: "مجموعة جديدة",
    kp1: "تسجيلات في انتظار القبول", kp2: "لاعبون نشطون", kp3: "دفوعات معلّقة", kp4: "معدّل الحضور",
    a_table: "التسجيلات في انتظار القبول", c_kid: "الطفل", c_parent: "الولي", c_group: "المجموعة", c_pay: "الدفع", c_act: "الإجراءات",
    paid: "مدفوع", wait: "في الانتظار", ok: "قبول", no: "رفض",
    a_groups: "المجموعات والمدربون", coach_sami: "المدرب سامي", players: "[ع] لاعبين",
    r1: "لينا", r1p: "سنية ب.", r1g: "U9 · الأربعاء والسبت",
    r2: "آدم", r2p: "كريم ط.", r2g: "U11 · الثلاثاء والخميس والسبت",
    r3: "إيناس", r3p: "أمل ر.", r3g: "U13 · الثلاثاء والخميس والسبت",
    r4: "وليد", r4p: "هشام م.", r4g: "U15 · الإثنين والأربعاء والجمعة",
    /* ajouts Laravel */
    hello: "مرحبًا", logout: "خروج", rej: "التسجيل مرفوض", noreg: "غير مسجّل بعد", register_now: "سجّل",
    no_kids: "لا يوجد أطفال حاليًا.", kid_new: "إضافة طفل", kid_edit: "تعديل البطاقة",
    f_first: "الاسم", f_last: "اللقب", f_birth: "تاريخ الولادة", f_medical: "معلومات صحية مفيدة (حساسية، ربو…)",
    f_photo: "أسمح بنشر صور طفلي", save: "حفظ",
    f_email_req: "البريد الإلكتروني", f_remember: "تذكّرني", no_account: "ليس لديك حساب؟", have_account: "لديك حساب؟",
    pend_title: "الدفع في عين المكان أو بالتحويل",
    pend_text: "الدفع عبر الإنترنت غير مفعّل بعد. ادفعوا المبلغ في الأكاديمية أو بالتحويل، وسيؤكّد المسؤول الدفع.",
    pend_amount: "المبلغ المطلوب", ret_title: "حالة الدفع", st_paid: "تم استلام الدفع. شكرًا!",
    st_pending: "الدفع في انتظار التأكيد.", st_failed: "فشلت عملية الدفع.", retry: "إعادة المحاولة", go_home: "العودة إلى الرئيسية",
    cg_title: "مجموعاتي", add_session: "إضافة حصة", date_time: "التاريخ والساعة", no_sessions: "لا توجد حصص مبرمجة.",
    open_call: "تسجيل الحضور", no_groups: "لم يتم إسناد أي مجموعة إليك.",
    session_history_link: "عرض جميع الحصص والسجل", session_history: "الحصص والسجل",
    session_history_sub: "اعثر على الحصص وسجلات الحضور حسب الفترة.", history_year: "السنة", history_month: "الشهر",
    history_all_months: "السنة كاملة", history_group: "المجموعة", history_all_groups: "جميع المجموعات",
    history_filter: "تصفية الحصص", history_call_done: "تم تسجيل الحضور", history_call_todo: "تسجيل الحضور مطلوب",
    history_call_edit: "عرض سجل الحضور أو تعديله", history_empty: "لم يتم العثور على حصص في هذه الفترة.",
    history_no_location: "المكان غير محدد"
  }
};

let lang = "fr";
try { lang = localStorage.getItem("lang") || "fr"; } catch (e) {}

function t(key) { return (T[lang] && T[lang][key]) || key; }

function setLang(l) {
  lang = l;
  document.documentElement.lang = l;
  document.documentElement.dir = l === "ar" ? "rtl" : "ltr";
  document.querySelectorAll("[data-i18n]").forEach(function (el) {
    var v = T[l][el.dataset.i18n];
    if (v !== undefined) el.textContent = v;
  });
  document.querySelectorAll("[data-i18n-placeholder]").forEach(function (el) {
    var v = T[l][el.dataset.i18nPlaceholder];
    if (v !== undefined) el.placeholder = v;
  });
  document.querySelectorAll("[data-i18n-label]").forEach(function (el) {
    var v = T[l][el.dataset.i18nLabel];
    if (v !== undefined) el.setAttribute("aria-label", v);
  });
  var b = document.getElementById("langBtn");
  if (b) b.textContent = T[l].langBtn;
  try { localStorage.setItem("lang", l); } catch (e) {}
  // Le cookie permet aux requêtes Laravel (validation, connexion, etc.)
  // d'utiliser la même langue que l'interface côté navigateur.
  document.cookie = "locale=" + l + "; path=/; max-age=31536000; samesite=lax" +
    (location.protocol === "https:" ? "; secure" : "");
  document.dispatchEvent(new CustomEvent("langchange", { detail: l }));
}

document.addEventListener("DOMContentLoaded", function () {
  var b = document.getElementById("langBtn");
  if (b) b.addEventListener("click", function () { setLang(lang === "fr" ? "ar" : "fr"); });
  setLang(lang);
});
