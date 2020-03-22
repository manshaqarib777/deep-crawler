/*
 Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Afrikaans language.
 */

/**#@+
 @type String
 @example
 */

/**
 * Contains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['af'] =
{
    /**
     * The language reading direction. Possible values are "rtl" for
     * Right-To-Left languages (like Arabic) and "ltr" for Left-To-Right
     * languages (like English).
     * @default 'ltr'
     */
    dir: 'ltr',

    /*
     * Screenreader titles. Please note that screenreaders are not always capable
     * of reading non-English words. So be careful while translating it.
     */
    editorTitle: 'Teksverwerker, %1, druk op ALT 0 vir hulp.',

    // ARIA descriptions.
    toolbars: 'Editor toolbars', // MISSING
    editor: 'Teksverwerker',

    // Toolbar buttons without dialogs.
    source: 'Bron',
    newPage: 'Nuwe bladsy',
    save: 'Bewaar',
    preview: 'Voorbeeld',
    cut: 'Knip',
    copy: 'Kopi√´er',
    paste: 'Plak',
    print: 'Druk',
    underline: 'Onderstreep',
    bold: 'Vet',
    italic: 'Skuins',
    selectAll: 'Selekteer alles',
    removeFormat: 'Verwyder opmaak',
    strike: 'Deurstreep',
    subscript: 'Onderskrif',
    superscript: 'Bo-skrif',
    horizontalrule: 'Horisontale lyn invoeg',
    pagebreak: 'Bladsy-einde invoeg',
    pagebreakAlt: 'Bladsy-einde',
    unlink: 'Verwyder skakel',
    undo: 'Ontdoen',
    redo: 'Oordoen',

    // Common messages and labels.
    common: {
        browseServer: 'Blaai op bediener',
        url: 'URL',
        protocol: 'Protokol',
        upload: 'Oplaai',
        uploadSubmit: 'Stuur na bediener',
        image: 'Afbeelding',
        flash: 'Flash',
        form: 'Vorm',
        checkbox: 'Merkhokkie',
        radio: 'Radioknoppie',
        textField: 'Teksveld',
        textarea: 'Teks-area',
        hiddenField: 'Blinde veld',
        button: 'Knop',
        select: 'Keuseveld',
        imageButton: 'Afbeeldingsknop',
        notSet: '<geen instelling>',
        id: 'Id',
        name: 'Naam',
        langDir: 'Skryfrigting',
        langDirLtr: 'Links na regs (LTR)',
        langDirRtl: 'Regs na links (RTL)',
        langCode: 'Taalkode',
        longDescr: 'Lang beskrywing URL',
        cssClass: 'CSS klasse',
        advisoryTitle: 'Aanbevole titel',
        cssStyle: 'Styl',
        ok: 'OK',
        cancel: 'Kanselleer',
        close: 'Sluit',
        preview: 'Voorbeeld',
        generalTab: 'Algemeen',
        advancedTab: 'Gevorderd',
        validateNumberFailed: 'Hierdie waarde is nie \'n getal nie.',
        confirmNewPage: 'Alle wysiginge sal verlore gaan. Is u seker dat u \'n nuwe bladsy wil laai?',
        confirmCancel: 'Sommige opsies is gewysig. Is u seker dat u hierdie dialoogvenster wil sluit?',
        options: 'Opsies',
        target: 'Doel',
        targetNew: 'Nuwe venster (_blank)',
        targetTop: 'Boonste venster (_top)',
        targetSelf: 'Selfde venster (_self)',
        targetParent: 'Oorspronklike venster (_parent)',
        langDirLTR: 'Links na Regs (LTR)',
        langDirRTL: 'Regs na Links (RTL)',
        styles: 'Styl',
        cssClasses: 'CSS klasse',
        width: 'Breedte',
        height: 'Hoogte',
        align: 'Oplyn',
        alignLeft: 'Links',
        alignRight: 'Regs',
        alignCenter: 'Sentreer',
        alignTop: 'Bo',
        alignMiddle: 'Middel',
        alignBottom: 'Onder',
        invalidHeight: 'Hoogte moet \'n getal wees',
        invalidWidth: 'Breedte moet \'n getal wees.',
        invalidCssLength: 'Value specified for the "%1" field must be a positive number with or without a valid CSS measurement unit (px, %, in, cm, mm, em, ex, pt, or pc).', // MISSING
        invalidHtmlLength: 'Value specified for the "%1" field must be a positive number with or without a valid HTML measurement unit (px or %).', // MISSING
        invalidInlineStyle: 'Value specified for the inline style must consist of one or more tuples with the format of "name : value", separated by semi-colons.', // MISSING
        cssLengthTooltip: 'Enter a number for a value in pixels or a number with a valid CSS unit (px, %, in, cm, mm, em, ex, pt, or pc).', // MISSING

        // Put the voice-only part of the label in the span.
        unavailable: '%1<span class="cke_accessibility">, nie beskikbaar nie</span>'
    },

    contextmenu: {
        options: 'Konteks Spyskaart-opsies'
    },

    // Special char dialog.
    specialChar: {
        toolbar: 'Voeg spesiaale karakter in',
        title: 'Kies spesiale karakter',
        options: 'Spesiale karakter-opsies'
    },

    // Link dialog.
    link: {
        toolbar: 'Skakel invoeg/wysig',
        other: '<ander>',
        menu: 'Wysig skakel',
        title: 'Skakel',
        info: 'Skakel informasie',
        target: 'Doel',
        upload: 'Oplaai',
        advanced: 'Gevorderd',
        type: 'Skakelsoort',
        toUrl: 'URL',
        toAnchor: 'Anker in bladsy',
        toEmail: 'E-pos',
        targetFrame: '<raam>',
        targetPopup: '<opspringvenster>',
        targetFrameName: 'Naam van doelraam',
        targetPopupName: 'Naam van opspringvenster',
        popupFeatures: 'Eienskappe van opspringvenster',
        popupResizable: 'Herskaalbaar',
        popupStatusBar: 'Statusbalk',
        popupLocationBar: 'Adresbalk',
        popupToolbar: 'Werkbalk',
        popupMenuBar: 'Spyskaartbalk',
        popupFullScreen: 'Volskerm (IE)',
        popupScrollBars: 'Skuifbalke',
        popupDependent: 'Afhanklik (Netscape)',
        popupLeft: 'Posisie links',
        popupTop: 'Posisie bo',
        id: 'Id',
        langDir: 'Skryfrigting',
        langDirLTR: 'Links na regs (LTR)',
        langDirRTL: 'Regs na links (RTL)',
        acccessKey: 'Toegangsleutel',
        name: 'Naam',
        langCode: 'Taalkode',
        tabIndex: 'Tab indeks',
        advisoryTitle: 'Aanbevole titel',
        advisoryContentType: 'Aanbevole inhoudstipe',
        cssClasses: 'CSS klasse',
        charset: 'Karakterstel van geskakelde bron',
        styles: 'Styl',
        rel: 'Relationship', // MISSING
        selectAnchor: 'Kies \'n anker',
        anchorName: 'Op ankernaam',
        anchorId: 'Op element Id',
        emailAddress: 'E-posadres',
        emailSubject: 'Berig-onderwerp',
        emailBody: 'Berig-inhoud',
        noAnchors: '(Geen ankers beskikbaar in dokument)',
        noUrl: 'Gee die skakel se URL',
        noEmail: 'Gee die e-posadres'
    },

    // Anchor dialog
    anchor: {
        toolbar: 'Anker byvoeg/verander',
        menu: 'Anker-eienskappe',
        title: 'Anker-eienskappe',
        name: 'Ankernaam',
        errorName: 'Voltooi die ankernaam asseblief',
        remove: 'Remove Anchor' // MISSING
    },

    // List style dialog
    list: {
        numberedTitle: 'Eienskappe van genommerde lys',
        bulletedTitle: 'Eienskappe van ongenommerde lys',
        type: 'Tipe',
        start: 'Begin',
        validateStartNumber: 'Beginnommer van lys moet \'n heelgetal wees.',
        circle: 'Sirkel',
        disc: 'Skyf',
        square: 'Vierkant',
        none: 'Geen',
        notset: '<nie ingestel nie>',
        armenian: 'Armeense nommering',
        georgian: 'Georgiese nommering (an, ban, gan, ens.)',
        lowerRoman: 'Romeinse kleinletters (i, ii, iii, iv, v, ens.)',
        upperRoman: 'Romeinse hoofletters (I, II, III, IV, V, ens.)',
        lowerAlpha: 'Kleinletters (a, b, c, d, e, ens.)',
        upperAlpha: 'Hoofletters (A, B, C, D, E, ens.)',
        lowerGreek: 'Griekse kleinletters (alpha, beta, gamma, ens.)',
        decimal: 'Desimale syfers (1, 2, 3, ens.)',
        decimalLeadingZero: 'Desimale syfers met voorloopnul (01, 02, 03, ens.)'
    },

    // Find And Replace Dialog
    findAndReplace: {
        title: 'Soek en vervang',
        find: 'Soek',
        replace: 'Vervang',
        findWhat: 'Soek na:',
        replaceWith: 'Vervang met:',
        notFoundMsg: 'Teks nie gevind nie.',
        findOptions: 'Find Options', // MISSING
        matchCase: 'Hoof/kleinletter sensitief',
        matchWord: 'Hele woord moet voorkom',
        matchCyclic: 'Soek deurlopend',
        replaceAll: 'Vervang alles',
        replaceSuccessMsg: '%1 voorkoms(te) vervang.'
    },

    // Table Dialog
    table: {
        toolbar: 'Tabel',
        title: 'Tabel eienskappe',
        menu: 'Tabel eienskappe',
        deleteTable: 'Verwyder tabel',
        rows: 'Rye',
        columns: 'Kolomme',
        border: 'Randbreedte',
        widthPx: 'piksels',
        widthPc: 'persent',
        widthUnit: 'breedte-eenheid',
        cellSpace: 'Sel-afstand',
        cellPad: 'Sel-spasie',
        caption: 'Naam',
        summary: 'Opsomming',
        headers: 'Opskrifte',
        headersNone: 'Geen',
        headersColumn: 'Eerste kolom',
        headersRow: 'Eerste ry',
        headersBoth: 'Beide    ',
        invalidRows: 'Aantal rye moet \'n getal groter as 0 wees.',
        invalidCols: 'Aantal kolomme moet \'n getal groter as 0 wees.',
        invalidBorder: 'Randbreedte moet \'n getal wees.',
        invalidWidth: 'Tabelbreedte moet \'n getal wees.',
        invalidHeight: 'Tabelhoogte moet \'n getal wees.',
        invalidCellSpacing: 'Sel-afstand moet \'n getal wees.',
        invalidCellPadding: 'Sel-spasie moet \'n getal wees.',

        cell: {
            menu: 'Sel',
            insertBefore: 'Voeg sel in voor',
            insertAfter: 'Voeg sel in na',
            deleteCell: 'Verwyder sel',
            merge: 'Voeg selle saam',
            mergeRight: 'Voeg saam na regs',
            mergeDown: 'Voeg saam ondertoe',
            splitHorizontal: 'Splits sel horisontaal',
            splitVertical: 'Splits sel vertikaal',
            title: 'Sel eienskappe',
            cellType: 'Sel tipe',
            rowSpan: 'Omspan rye',
            colSpan: 'Omspan kolomme',
            wordWrap: 'Woord terugloop',
            hAlign: 'Horisontale oplyning',
            vAlign: 'Vertikale oplyning',
            alignBaseline: 'Basislyn',
            bgColor: 'Agtergrondkleur',
            borderColor: 'Randkleur',
            data: 'Inhoud',
            header: 'Opskrif',
            yes: 'Ja',
            no: 'Nee',
            invalidWidth: 'Selbreedte moet \'n getal wees.',
            invalidHeight: 'Selhoogte moet \'n getal wees.',
            invalidRowSpan: 'Omspan rye moet \'n heelgetal wees.',
            invalidColSpan: 'Omspan kolomme moet \'n heelgetal wees.',
            chooseColor: 'Kies'
        },

        row: {
            menu: 'Ry',
            insertBefore: 'Voeg ry in voor',
            insertAfter: 'Voeg ry in na',
            deleteRow: 'Verwyder ry'
        },

        column: {
            menu: 'Kolom',
            insertBefore: 'Voeg kolom in voor',
            insertAfter: 'Voeg kolom in na',
            deleteColumn: 'Verwyder kolom'
        }
    },

    // Button Dialog.
    button: {
        title: 'Knop eienskappe',
        text: 'Teks (Waarde)',
        type: 'Soort',
        typeBtn: 'Knop',
        typeSbm: 'Stuur',
        typeRst: 'Maak leeg'
    },

    // Checkbox and Radio Button Dialogs.
    checkboxAndRadio: {
        checkboxTitle: 'Merkhokkie eienskappe',
        radioTitle: 'Radioknoppie eienskappe',
        value: 'Waarde',
        selected: 'Geselekteer'
    },

    // Form Dialog.
    form: {
        title: 'Vorm eienskappe',
        menu: 'Vorm eienskappe',
        action: 'Aksie',
        method: 'Metode',
        encoding: 'Kodering'
    },

    // Select Field Dialog.
    select: {
        title: 'Keuseveld eienskappe',
        selectInfo: 'Info',
        opAvail: 'Beskikbare opsies',
        value: 'Waarde',
        size: 'Grootte',
        lines: 'Lyne',
        chkMulti: 'Laat me       @           ú   –         ¸   IDENTITYCRL_CERT_CONTAINER_ec1bc145-f460-4231-9989-a62316a1f3d3                     RSA1à           1Û¿¥ø`¿3;˝UB]öÈ⁄=âe—$4øÚF∆§o(#pO£¯˘ÆÎêù}±jÂ‘CbCAóûÃ_Mêπœô≈£‚ÆoÁ*ÏÆÆ:≠v'ÿ—òúﬂ\&#U9ÕELÅƒ‚ºŸ7ulÕdnÇ÷~£ÓüL6j›73Ôõ˘®≠˙qõ           –åùﬂ—åz ¿O¬óÎ   	›ºkµGCëp|G‰∫Ô    ,   C r y p t o A P I   P r i v a t e   K e y   f         NÕ{èçwª=|ãáˆÒ§I«
–f˝üﬂû    Ä         F0!t¸Y∏ì≠§ˇ¸Í$[◊'»4„%Û˜&æ'L–  
5ÄOü∫‹Ûo©*_JXæﬁÚô }4˜Å›üvcæ4`}(p…í/l^]_°¸À0‚®pwmkœ¯bÇ€`Î´‚é–#(A]˘MÊFq‹;s£vÏÔ ¶òÉ¥¬ã|‘ˇ«f8¿^(…Ì‹Ë8¢£Iûtá†(6äO)xX†nØÛ«3ñ0)ÁZµà>ûŸÏ\ãœÆå]k‰oÅ7V
Ç[)S›80 ∑Ì≥£Ø^h<{!Älï%¶§´I±Ïa Ñâ*)˙0ö„˝(|ê‹¬ˆÆõ›,Oa#G¯≤†ç¨æ•™ô§ â^âxj=:2€ø¸m°–x÷\Ô`,Aú=·∑ˆC	1yã„˜©Oùèk∞’ûoó2a‹S˜›\ŒÿdÜìÙ{.ÄhÃ9ÏÛLﬁ0ıxÒ1≈+r«Ë3„[µ6(CDê0á™¡8‘Mó_îÔAπÂ"+d7ˆOzÎ·S˘ÅÌfk¨	eFHÄ¿†~Â:R§ç‹kÈÑ^XË~¯h79Kz	•ısÑˆ8€ˇÁ‰5†±y Õ1Cßâ~ÏTœ%(`⁄ñ9°√©>ASr_§˝ﬁÉ=Œ(:}”/9»œû°¬ÈkoqÖ£Ÿ	g®à∞	zÔY}¬pÍ¬8„IcÌπÍÜ˚Êqπ´ßì-X˝Ù”ŸBbs—/Än◊kè¢XRÓZ¶ûÂ€íH/:qü8—ÕMz5ëƒπFScW¢ë–ê4D™§FÏ/öÃN 8uÜ1ÃR>ì≤?y6ï¬˝ﬂ√r‰∑4¸2K[é5ˇ"≤Xk3+,jÓ’%Ë÷œ˝∞\']}ø6±·∆St2 ¬Yó“%˘L&%„	˛ˇÒÂä‘©ı‚ÓºpI‡¥D_á+6jv\ó˜Û}”ê‘´ÈQµRM\Ól6_ËV4Â.åù.C∫[µwsx^Ôôßxæ@@   B±ñ±†Föxª[çŒª%˝–|˜«ˆíô—Ÿú^~e	z˚BµÓ6–RPIE©Æ’yBs∞eø≈¿í   –åùﬂ—åz ¿O¬óÎ   	›ºkµGCëp|G‰∫Ô       E x p o r t   F l a g   f         *Ã«ﬁ?›^˜ı]õÊmïcbëW\à=hÊè
ŸC„    Ä         ¬n⁄˙≥}a—}ÊP∑±>pb^◊Q’Á a4©àîsóm   z˚AL‰FÓ°(–*,‘Ω´@   3ˇ∑ƒ|⁄ÁÑÁ:õ5Ò∆	I®@yUîZÅ¨Ω@˙ïπÄ¢é—VÚ÷–ﬁáîÛÑI&≈O•EêêÛŸ≠Qÿe®                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   @           ú   –         ¸   IDENTITYCRL_CERT_CONTAINER_86bb5fd4-ec83-4ef6-bba5-fa9be4638956                     RSA1à           %Å\N‘.ÛZñàÕÍ‚ÈúxrùjïÚªBÔ£i<7K(êL0%˘ÊˇMØC˛kÃ£µ,9ÜªSMÊŒHﬁ¶ØGE?VÜ∑xhö57sàÏeõ0D»ˆñ¸¸lFAÄ¿µ%1»äﬂÇ)Ó)ôÖ˙?çVÛø≤;≈˛áΩG˙(!`ƒ           –åùﬂ—åz ¿O¬óÎ   {SïÍu>(Oúˇv≤∂ﬁY0    ,   C r y p t o A P I   P r i v a t e   K e y   f         )ö&≈î©$‡Æ$áI0©8d'R˘ñ2çÆn|˚/    Ä         §ÂòØ≤Ï=YÁ/£ó∂∫	ßÖ®ˆõ’¨H· –  ≥ éÛ”E∂®FüÅXÛ‘¡"â8Ä1E*‹AóÛûÛãö›è^iyg¸∂‘JÀB^#ç%ˆQB&ÿèR”©Y‘Œ
≤ª}6Z≤Mûº5ä%[∑u¶˛-sAùé{±ÌoXª≤÷Sa-ÁÙÀ¶¨Ïå$Ωk [†á„6îbæ>˜’€∆óO'4Z≥âSkÈä·ä£^Ñàç√”«ó.i;°MÅ˚Y<$|*ugfOâc°y¶ =¸Uiœ⁄+˝4ò«eëy¬ ∂ﬂ‘Éà‹„;È¯ z“Ç.Ñ°uëÒ„Õkœê5ô∂Äü¡âF[¬Æv=íŸb5kP./g…«3˜ÄuRß™or#ƒè.Î≥)F,˛›≈‘7¢öÏ≥˚2í}ü~M)eÂ4°ò?bfÜSn˝ıc°⁄et„Êœ˛W¨ıê‚‘ùuïÔ˚Z?W|lO¨+Xâ	Ì”˜Ñf<©˜’ q˚•Î\wœ…¬∞¯H›—G◊ ◊4{µ%≤æ¨âr¶sëD~9C<êÏ6≤œ≠èÆÎ+8»L±∏s˙¶åpCIz®#∫b=åü‚{€ò◊ãIç'∫ADP¥Ì1◊I›”Í‹ˆ-ò˜ÌZ¢7GNUN«Èç»¢n0œµΩ¨‚A"á7∑tØ.+PØñh=áµıX˛GÕ±Cé∆¢ªÆ3◊öÉê^™l‰%oWº	h)â7o RáÛ\Ã§rñ3\$´kºydã2·Ö
7`|TH¨9´€,≤™‡Ã#F—åô•Œ… ∞~¡in}Û^Ñ/&§üí€Z≠ˇ†)«)¡óOv#˚⁄¸˛˜≈•ÅÜ‰Nå~G ˇ&∫q¸Çéuå/˙á,ùÓ˜BsKÛKÚ·¢kÿ Ø;d˜ã]î\dÂNre$ñYÿ	`œ£$*Uú‚òxÊ&›◊e⁄·Pvıxñ≠≈‹@   ÖåÜàçù√ÒøE 
´óÊíÎ≈≈úµl{™zÛûÁ5Îh*¡Ë∫£©GÆNaywëN¿}Ê\§¬eYØ§ÿÌ÷   –åùﬂ—åz ¿O¬óÎ   {SïÍu>(Oúˇv≤∂ﬁY0       E x p o r t   F l a g   f         °5á∫öº1ø“LÎÛ•≥5àK«õ›¬w≈oƒ¨
G    Ä         1êÃÌuAon.9ﬂ€ZzøÑﬁÜ›·\ó°Ω±·pÕrπ   ó{§à‡ÀsÅ}GsK≠ä]@   ÓÕÎ—∂Öz∞ßÂPÄä5=“¢PÜçÙ·ÜJ>ÄîπmÑ¥é∆®¡P^‰∑œÓ@{∞™/X|VT¥f¶C›h16                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            rleisteengrys',
        '008080': 'Blougroen',
        '000080': 'Vlootblou',
        '4B0082': 'Indigo',
        '696969': 'Donkergrys',
        'B22222': 'Rooibaksteen',
        'A52A2A': 'Bruin',
        'DAA520': 'Donkergeel',
        '006400': 'Donkergroen',
        '40E0D0': 'Turkoois',
        '0000CD': 'Middelblou',
        '800080': 'Pers',
        '808080': 'Grys',
        'F00': 'Rooi',
        'FF8C00': 'Donkeroranje',
        'FFD700': 'Goud',
        '008000': 'Groen',
        '0FF': 'Siaan',
        '00F': 'Blou',
        'EE82EE': 'Viooltjieblou',
        'A9A9A9': 'Donkergrys',
        'FFA07A': 'Ligsalm',
        'FFA500': 'Oranje',
        'FFFF00': 'Geel',
        '00FF00': 'Lemmetjie',
        'AFEEEE': 'Ligturkoois',
        'ADD8E6': 'Ligblou',
        'DDA0DD': 'Pruim',
        'D3D3D3': 'Liggrys',
        'FFF0F5': 'Linne',
        'FAEBD7': 'Ivoor',
        'FFFFE0': 'Liggeel',
        'F0FFF0': 'Heuningdou',
        'F0FFFF': 'Asuur',
        'F0F8FF': 'Ligte hemelsblou',
        'E6E6FA': 'Laventel',
        'FFF': 'Wit'
    },

    scayt: {
        title: 'Speltoets terwyl u tik',
        opera_title: 'Nie ondersteun deur Opera nie',
        enable: 'SCAYT aan',
        disable: 'SCAYT af',
        about: 'SCAYT info',
        toggle: 'SCAYT wissel aan/af',
        options: 'Opsies',
        langs: 'Tale',
        moreSuggestions: 'Meer voorstelle',
        ignore: 'Ignoreer',
        ignoreAll: 'Ignoreer alles',
        addWord: 'Voeg woord by',
        emptyDic: 'Woordeboeknaam mag nie leeg wees nie.',

        optionsTab: 'Opsies',
        allCaps: 'Ignoreer woorde in hoofletters',
        ignoreDomainNames: 'Ignoreer domeinname',
        mixedCase: 'Ignoreer woorde met hoof- en kleinletters',
        mixedWithDigits: 'Ignoreer woorde met syfers',

        languagesTab: 'Tale',

        dictionariesTab: 'Woordeboeke',
        dic_field_name: 'Naam van woordeboek',
        dic_create: 'Skep',
        dic_restore: 'Herstel',
        dic_delete: 'Verwijder',
        dic_rename: 'Hernoem',
        dic_info: 'Aanvanklik word die gebruikerswoordeboek in \'n koekie gestoor. Koekies is egter beperk in grootte. Wanneer die gebruikerswoordeboek te groot vir \'n koekie geword het, kan dit op ons bediener gestoor word. Om u persoonlike woordeboek op ons bediener te stoor, gee asb. \'n naam vir u woordeboek. Indien u alreeds \'n gestoorde woordeboek het, tik die naam en kliek op die Herstel knop.',

        aboutTab: 'Info'
    },

    about: {
        title: 'Info oor CKEditor',
        dlgTitle: 'Info oor CKEditor',
        help: 'Check $1 for help.', // MISSING
        userGuide: 'CKEditor User\'s Guide', // MISSING
        moreInfo: 'Vir lisensie-informasie, besoek asb. ons webwerf:',
        copy: 'Kopiereg &copy; $1. Alle regte voorbehou.'
    },

    maximize: 'Maksimaliseer',
    minimize: 'Minimaliseer',

    fakeobjects: {
        anchor: 'Anker',
        flash: 'Flash animasie',
        iframe: 'IFrame',
        hiddenfield: 'Verborge veld',
        unknown: 'Onbekende objek'
    },

    resize: 'Sleep om te herskaal',

    colordialog: {
        title: 'Kies kleur',
        options: 'Kleuropsies',
        highlight: 'Aktief',
        selected: 'Geselekteer',
        clear: 'Herstel'
    },

    toolbarCollapse: 'Verklein werkbalk',
    toolbarExpand: 'Vergroot werkbalk',

    toolbarGroups: {
        document: 'Document', // MISSING
        clipboard: 'Clipboard/Undo', // MISSING
        editing: 'Editing', // MISSING
        forms: 'Forms', // MISSING
        basicstyles: 'Basic Styles', // MISSING
        paragraph: 'Paragraph', // MISSING
        links: 'Links', // MISSING
        insert: 'Insert', // MISSING
        styles: 'Styles', // MISSING
        colors: 'Colors', // MISSING
        tools: 'Tools' // MISSING
    },

    bidi: {
        ltr: 'Skryfrigting van links na regs',
        rtl: 'Skryfrigting van regs na links'
    },

    docprops: {
        label: 'Dokument Eienskappe',
        title: 'Dokument Eienskappe',
        design: 'Design', // MISSING
        meta: 'Meta Data',
        chooseColor: 'Kies',
        other: '<ander>',
        docTitle: 'Bladsy Opskrif',
        charset: 'Karakterstel Kodeering',
        charsetOther: 'Ander Karakterstel Kodeering',
        charsetASCII: 'ASCII', // MISSING
        charsetCE: 'Sentraal Europa',
        charsetCT: 'Chinees Traditioneel (Big5)',
        charsetCR: 'Cyrillic', // MISSING
        charsetGR: 'Grieks',
        charsetJP: 'Japanees',
        charsetKR: 'Koreans',
        charsetTR: 'Turks',
        charsetUN: 'Unicode (UTF-8)', // MISSING
        charsetWE: 'Western European', // MISSING
        docType: 'Dokument Opskrif Soort',
        docTypeOther: 'Ander Dokument Opskrif Soort',
        xhtmlDec: 'Voeg XHTML verklaring by',
        bgColor: 'Agtergrond kleur',
        bgImage: 'Agtergrond Beeld URL',
        bgFixed: 'Vasgeklemde Agtergrond',
        txtColor: 'Tekskleur',
        margin: 'Bladsy Rante',
        marginTop: 'Bo',
        marginLeft: 'Links',
        marginRight: 'Regs',
        marginBottom: 'Onder',
        metaKeywords: 'Dokument Index Sleutelwoorde(comma verdeelt)',
        metaDescription: 'Dokument Beskrywing',
        metaAuthor: 'Skrywer',
        metaCopyright: 'Kopiereg',
        previewHtml: '<p>This is some <strong>sample text</strong>. You are using <a href="javascript:void(0)">CKEditor</a>.</p>' // MISSING
    }
};
