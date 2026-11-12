// ==========================================
// 1. ZENTRALES COLOR-THEME (Tailwind v4 / CSS)
// ==========================================
export const themeColors = {
  primary: "orange-650", // Hauptakzent (z. B. für Buttons, aktive Links)
  primaryHover: "orange-700", // Hover-Zustand für Primär-Elemente
  bgDark: "stone-950", // Haupt-Hintergrund (Dunkelmodus)
  surfaceDark: "stone-900", // Boxen / Karten Hintergrund
  textLight: "stone-100", // Haupttextfarbe
  textMuted: "stone-400", // Nebentextfarbe
  borderDark: "stone-800", // Rahmenfarben
};

// ==========================================
// 2. GLOBALE KONTAKTDATEN (Einmalig, keine Duplikation)
// ==========================================
export const siteConfig = {
  name: "Selami Akosman",
  brand: "Fahrradschmiede Berlin",
  owner: "Selami Akosman",
  domain: "fahrradschmiede-berlin.de",
  street: "Kolonnenstraße 48",
  zip: "10829",
  city: "Berlin (Tempelhof-Schöneberg)",
  address: "Kolonnenstraße 48, 10829 Berlin",
  phone: "030 7827898",
  email: "info@bikeparkberlin.de",
  web: "fahrradschmiede-berlin.de",
  taxId: "DE 266 459 789",
  openingHours: "Mo-Fr: 10:00 - 19:00 Uhr | Sa: 10:00 - 15:00 Uhr",
};

// ==========================================
// 3. ZENTRALES SPRACH-WÖRTERBUCH (i18n - nur Texte)
// ==========================================
export const languages = {
  de: {
    logo: {
      part1: "FAHRRAD",
      part2: "SCHMIEDE",
      part3: "BERLIN",
    },
    subtitle: "Meisterbetrieb für Fahrräder & Service",
    description:
      "Ihr kompetenter Ansprechpartner für hochwertige Fahrräder, präzisen Werkstatt-Service und individuelle Beratung in Berlin.",
    nav: {
      bikes: "Fahrräder",
      contact: "Kontakt",
    },
    hero: {
      badge: "Meisterbetrieb in Berlin",
      title1: "Fahrradschmiede",
      title2: "Berlin",
      description:
        "Ihr kompetenter Ansprechpartner für hochwertige Fahrräder, präzisen Werkstatt-Service und individuelle Beratung in der Hauptstadt.",
      btnBikes: "Fahrräder ansehen",
      btnContact: "Kontakt aufnehmen",
    },
    contact: {
      title: "Kontakt",
      desc: "Haben Sie Fragen zu unseren Fahrrädern oder benötigen Sie einen Werkstatt-Termin? Wir sind für Sie da.",
      visitUs: "Besuchen Sie uns",
      formName: "Ihr Name",
      formEmail: "E-Mail-Adresse",
      formSubject: "Betreff",
      formMessage: "Nachricht",
      formSubmit: "Nachricht absenden",
      successMsg: "Vielen Dank! Ihre Nachricht wurde erfolgreich versendet.",
      errorMsg:
        "Ein Fehler ist aufgetreten. Bitte versuchen Sie es später noch einmal.",
    },
    footer: {
      rights: "Alle Rechte vorbehalten.",
    },
  },
  en: {
    logo: {
      part1: "BICYCLE",
      part2: "FORGE",
      part3: "BERLIN",
    },
    subtitle: "Master Workshop for Bicycles & Service",
    description:
      "Your trusted partner for high-quality bicycles, precise workshop service, and expert individual consultation in Berlin.",
    nav: {
      bikes: "Bikes",
      contact: "Contact",
    },
    hero: {
      badge: "Master Workshop in Berlin",
      title1: "Fahrradschmiede",
      title2: "Berlin",
      description:
        "Your trusted partner for high-quality bicycles, precise workshop service, and expert individual consultation in the capital.",
      btnBikes: "View Bikes",
      btnContact: "Get in Touch",
    },
    contact: {
      title: "Contact",
      desc: "Do you have any questions about our bikes or need to book a workshop appointment? We're here to help.",
      visitUs: "Visit Us",
      formName: "Your Name",
      formEmail: "Email Address",
      formSubject: "Subject",
      formMessage: "Message",
      formSubmit: "Send Message",
      successMsg: "Thank you! Your message has been sent successfully.",
      errorMsg: "An error occurred. Please try again later.",
    },
    footer: {
      rights: "All rights reserved.",
    },
  },
  fr: {
    logo: {
      part1: "FORGE",
      part2: "VÉLO",
      part3: "BERLIN",
    },
    subtitle: "Atelier maître pour vélos et service",
    description:
      "Votre partenaire de confiance pour des vélos de haute qualité, un service d'atelier précis et des conseils personnalisés à Berlin.",
    nav: {
      bikes: "Vélos",
      contact: "Contact",
    },
    hero: {
      badge: "Atelier maître à Berlin",
      title1: "Fahrradschmiede",
      title2: "Berlin",
      description:
        "Votre partenaire de confiance pour des vélos de haute qualité, un service d'atelier précis et des conseils personnalisés dans la capitale.",
      btnBikes: "Voir les vélos",
      btnContact: "Nous contacter",
    },
    contact: {
      title: "Contact",
      desc: "Vous avez des questions sur nos vélos ou besoin d'un rendez-vous à l'atelier ? Nous sommes à votre disposition.",
      visitUs: "Visitez-nous",
      formName: "Votre nom",
      formEmail: "Adresse e-mail",
      formSubject: "Sujet",
      formMessage: "Message",
      formSubmit: "Envoyer le message",
      successMsg: "Merci ! Votre message a bien été envoyé.",
      errorMsg: "Une erreur s'est produite. Veuillez réessayer plus tard.",
    },
    footer: {
      rights: "Tous droits réservés.",
    },
  },
  tr: {
    logo: {
      part1: "BİSİKLET",
      part2: "ATÖLYESİ",
      part3: "BERLİN",
    },
    subtitle: "Bisiklet ve Servis Usta Atölyesi",
    description:
      "Berlin'de yüksek kaliteli bisikletler, kusursuz atölye hizmeti ve uzman danışmanlık için güvenilir adresiniz.",
    nav: {
      bikes: "Bisikletler",
      contact: "İletişim",
    },
    hero: {
      badge: "Berlin'de Usta Atölyesi",
      title1: "Fahrradschmiede",
      title2: "Berlin",
      description:
        "Başkentte yüksek kaliteli bisikletler, kusursuz atölye hizmeti ve bireysel danışmanlık için güvenilir adresiniz.",
      btnBikes: "Bisikletleri İncele",
      btnContact: "İletişime Geç",
    },
    contact: {
      title: "İletişim",
      desc: "Bisikletlerimiz hakkında sorularınız mı var veya servis randevusu mu almak istiyorsunuz? Size yardımcı olmaktan memnuniyet duyarız.",
      visitUs: "Bizi Ziyaret Edin",
      formName: "Adınız",
      formEmail: "E-posta Adresi",
      formSubject: "Konu",
      formMessage: "Mesaj",
      formSubmit: "Mesajı Gönder",
      successMsg: "Teşekkürler! Mesajınız başarıyla gönderildi.",
      errorMsg: "Bir hata oluştu. Lütfen daha sonra tekrar deneyin.",
    },
    footer: {
      rights: "Tüm hakları saklıdır.",
    },
  },
};

// Aktive Sprache festlegen (Standard: Deutsch)
export const currentLang = "de";
export const t = languages[currentLang];

// ==========================================
// 4. HELPER FUNKTIONEN (Zukunftssicher)
// ==========================================
export const getFormattedDate = (date, lang = currentLang) => {
  if (!date) return "";

  const localeMap = {
    de: "de-DE",
    en: "en-US",
    fr: "fr-FR",
    tr: "tr-TR",
  };

  const targetLocale = localeMap[lang] || `${lang}-${lang.toUpperCase()}`;

  return new Date(date).toLocaleDateString(targetLocale, {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};
