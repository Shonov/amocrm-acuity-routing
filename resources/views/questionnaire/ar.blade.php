<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('/img/questionnaire/favicon.png')}}" type="image/x-icon">
    <title>Questionnaire</title>
    <link href="{{asset('/css/questionnaire/ar/style.bundle.css')}}" rel="stylesheet">
</head>
<body>
<div class="app">
    <header class="app__header header"><a class="header__logo" href="/"><img class="header__logo-img"
                                                                             src="{{asset('/img/questionnaire/logo.png')}}"></a><a
                class="header__instagram-container" href="http://www.instagram.com/nexfitkw/" target="_blank"><img
                    class="header__instagram-logo" src="{{asset('/img/questionnaire/instagram-logo.png')}}">
            <div class="header__instagram-account">@nexfitkw</div>
        </a></header>
    <main class="app__main">
        <form class="form form--first-page" novalidate="novalidate">
            <div class="form__header form-header form-header--first-page">
                <div class="form-header__title-container">
                    <div class="form-header__title">كل شيء هنا عنك</div>
                    <div class="form-header__part">(الجزء 1 من 5)</div>
                </div>
                <div class="form-header__description">عرّفنا بنفسك</div>
                <div class="form-header__progress-bar">
                    <div class="form-header__progress"></div>
                </div>
            </div>
            <div class="form__body form-body">
                <div class="form-body__row form-input">
                    <div class="form-input__label">ما هو اسمك بالكامل؟</div>
                    <input class="form-input__input required" type="text" name="name"></div>
                <div class="form-body__row form-input">
                    <div class="form-input__label">ما هو البريد الإلكتروني الخاص بك؟</div>
                    <input class="form-input__input required" type="email" name="email"></div>
                <div class="form-body__row form-input">
                    <div class="form-input__label">ما هو جنسك؟</div>
                    <div class="form-input__checkbox-area">
                        <div class="form-input__checkbox-item"><input class="form-input__checkbox" id="sex__woman"
                                                                      type="radio" name="sex" value="woman"
                                                                      required="required"><label
                                    class="form-input__checkbox-checker form-input__checkbox-checker--sex-woman"
                                    for="sex__woman"></label></div>
                        <div class="form-input__checkbox-item"><input class="form-input__checkbox" id="sex__man"
                                                                      type="radio" name="sex" value="man"
                                                                      required="required"><label
                                    class="form-input__checkbox-checker" for="sex__man"></label></div>
                    </div>
                </div>
                <div class="form-body__row form-input">
                    <div class="form-input__label">كم عمرك؟</div>
                    <input class="form-input__input required" type="number" name="age"></div>
                <div class="form-body__row form-input">
                    <div class="form-input__label">ما هو طولك؟</div>
                    <input class="form-input__input required" type="number" name="height"></div>
                <div class="form-body__row form-input">
                    <div class="form-input__label">ما هو وزنك؟</div>
                    <input class="form-input__input required" type="number" name="weight"></div>
                <div class="form-body__row form-input"><input class="form-input__submit" type="submit" value="التالي">
                </div>
            </div>
        </form>
        <form class="form form--second-page" novalidate="novalidate">
            <div class="form__header form-header form-header--second-page">
                <div class="form-header__title-container">
                    <div class="form-header__title">كل شيء هنا عنك</div>
                    <div class="form-header__part">(الجزء2 من 5)</div>
                </div>
                <div class="form-header__description">كل شيء هنا عنك قم بوصف وضع لياقتك البدنية حاليًا</div>
                <div class="form-header__progress-bar">
                    <div class="form-header__progress form-header__progress--40"></div>
                </div>
            </div>
            <div class="form__body form-body">
                <div class="form-body__row form-input form-input--subtitle">
                    <div class="form-input__subtitle">كيف تقيم نفسك حاليًا؟</div>
                </div>
                <div class="form-body__row form-input form-input--range">
                    <div class="form-input__range-left">مرهق</div>
                    <input id="ex1" type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1"
                           data-slider-value="5">
                    <div class="form-input__range-right">مليء بالطاقة</div>
                </div>
                <div class="form-body__row form-input form-input--range">
                    <div class="form-input__range-left">بالكاد تستطيع المشي</div>
                    <input id="ex2" type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1"
                           data-slider-value="5">
                    <div class="form-input__range-right">حريص على القيام ببعض التمارين الثقيلة</div>
                </div>
                <div class="form-body__row form-input form-input--range">
                    <div class="form-input__range-left">قلق</div>
                    <input id="ex3" type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1"
                           data-slider-value="5">
                    <div class="form-input__range-right">هادئ</div>
                </div>
                <div class="form-body__row form-input form-input--subtitle">
                    <div class="form-input__subtitle">كيف حال جسمك؟</div>
                </div>
                <div class="form-body__row form-input form-input--range">
                    <div class="form-input__range-left form-input__range-left--with-image">
                        <div class="form-input__range-image form-input__range-image--fat mobile-hidden"></div>
                        سمين
                    </div>
                    <div class="form-input__range-left form-input__range-left--with-image pc-hidden form-input__solo-image">
                        <div class="form-input__range-image form-input__range-image--fat"></div>
                    </div>
                    <input id="ex4" type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1"
                           data-slider-value="5">
                    <div class="form-input__range-right">نحيل وهزيل
                        <div class="form-input__range-image form-input__range-image--landr mobile-hidden"></div>
                    </div>
                    <div class="form-input__range-right pc-hidden form-input__solo-image pc-hidden">
                        <div class="form-input__range-image form-input__range-image--landr"></div>
                    </div>
                </div>
                <div class="form-body__row form-input form-input--subtitle">
                    <div class="form-input__subtitle">ماذا عن القدرة على التحمل لديك؟</div>
                </div>
                <div class="form-body__row form-input form-input--range">
                    <div class="form-input__range-left form-input__range-left--with-image">
                        <div class="form-input__range-image form-input__range-image--exhausted mobile-hidden"></div>
                        تتنفس بسهولة
                    </div>
                    <div class="form-input__range-left form-input__range-left--with-image pc-hidden form-input__solo-image">
                        <div class="form-input__range-image form-input__range-image--exhausted"></div>
                    </div>
                    <input id="ex5" type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1"
                           data-slider-value="5">
                    <div class="form-input__range-right">يمكنني الجري بسباق ماراثون
                        <div class="form-input__range-image form-input__range-image--runner mobile-hidden"></div>
                    </div>
                    <div class="form-input__range-right pc-hidden form-input__solo-image">
                        <div class="form-input__range-image form-input__range-image--runner"></div>
                    </div>
                </div>
                <div class="form-body__row form-input form-input--subtitle">
                    <div class="form-input__subtitle">كم من الوقت يمكنك تخصيصه لممارسة التمارين أسبوعي؟</div>
                </div>
                <div class="form-body__row form-input form-input--range-one-line"><input id="ex13" type="text"
                                                                                         data-slider-tooltip="hide">
                </div>
                <div class="form-body__row form-input"><input class="form-input__submit" type="submit" value="التالي">
                </div>
            </div>
        </form>
        <form class="form form--third-page" novalidate="novalidate">
            <div class="form__header form-header form-header--third-page">
                <div class="form-header__title-container">
                    <div class="form-header__title">كل شيء هنا عنك</div>
                    <div class="form-header__part">(الجزء 3 من 5)</div>
                </div>
                <div class="form-header__description">ما الذي تتمنى تحقيقه؟</div>
                <div class="form-header__progress-bar">
                    <div class="form-header__progress form-header__progress--60"></div>
                </div>
            </div>
            <div class="form__body form-body">
                <div class="form-body__row form-input form-input--subtitle">
                    <div class="form-input__subtitle">ما الذي يوقفك عن أداء التمارين؟ (اختر كل ما ينطبق)</div>
                </div>
                <div class="form-body__row form-input form-input--column">
                    <div class="form-input__checkbox-area form-input__checkbox-area--full">
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="exercising__clock" type="checkbox"
                                                                          name="exercising" value="clock"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--clock"
                                        for="exercising__clock"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">ليس</div>
                            <div class="form-input__checkbox-label">لدي وقت</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="exercising__family" type="checkbox"
                                                                          name="exercising" value="family"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--family"
                                        for="exercising__family"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">لدي مسؤوليات</div>
                            <div class="form-input__checkbox-label">عائلية</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="exercising__question" type="checkbox"
                                                                          name="exercising" value="question"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--question-man"
                                        for="exercising__question"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">لا أعلم ما يجب
                            </div>
                            <div class="form-input__checkbox-label">القيام به</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="exercising__money" type="checkbox"
                                                                          name="exercising" value="money"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--money"
                                        for="exercising__money"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">ليس لدي</div>
                            <div class="form-input__checkbox-label">المال</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="exercising__quit" type="checkbox"
                                                                          name="exercising" value="quit"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--quit"
                                        for="exercising__quit"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">بدأت ثم توقفت
                            </div>
                            <div class="form-input__checkbox-label">بعد ذلك</div>
                        </div>
                        <div class="form-input__checkbox-container form-input__checkbox-container--with-hight-paddins pc-hidden">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="exercising__wheel_m" type="checkbox"
                                                                          name="exercising" value="wheel"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--wheel"
                                        for="exercising__wheel_m"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">أنا مريض أو لدي
                            </div>
                            <div class="form-input__checkbox-label">إعاقة</div>
                        </div>
                    </div>
                    <div class="form-input__checkbox-area form-input__checkbox-area--full form-input__checkbox-area--center">
                        <div class="form-input__checkbox-container form-input__checkbox-container--with-hight-paddins mobile-hidden">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="exercising__wheel" type="checkbox"
                                                                          name="exercising" value="wheel"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--wheel"
                                        for="exercising__wheel"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">أنا مريض أو لدي
                            </div>
                            <div class="form-input__checkbox-label">إعاقة</div>
                        </div>
                        <div class="form-input__checkbox-container form-input__checkbox-container--with-hight-paddins">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="exercising__fitness" type="checkbox"
                                                                          name="exercising" value="fitness"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--fitness"
                                        for="exercising__fitness"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">لا شيء أنا مهووس
                                باللياقة
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body__row form-input"><input class="form-input__submit" type="submit" value="التالي">
                </div>
            </div>
        </form>
        <form class="form form--fourth-page" novalidate="novalidate">
            <div class="form__header form-header form-header--fourth-page">
                <div class="form-header__title-container">
                    <div class="form-header__title">كل شيء هنا عنك</div>
                    <div class="form-header__part">(الجزء 4 من 5)</div>
                </div>
                <div class="form-header__description">لماذا لم تحقق ذلك بعد؟</div>
                <div class="form-header__progress-bar">
                    <div class="form-header__progress form-header__progress--80"></div>
                </div>
            </div>
            <div class="form__body form-body">
                <div class="form-body__row form-input form-input--subtitle">
                    <div class="form-input__subtitle">من سيكون سعيدًا برؤيتك بصحة جيدة؟ (اختر كل ما ينطبق)</div>
                </div>
                <div class="form-body__row form-input form-input--column">
                    <div class="form-input__checkbox-area form-input__checkbox-area--full">
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="arhive-it__partner" type="checkbox"
                                                                          name="arhive-it" value="partner"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--partner"
                                        for="arhive-it__partner"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">زوجي/زوجتي</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="arhive-it__kids" type="checkbox"
                                                                          name="arhive-it" value="kids"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--kids"
                                        for="arhive-it__kids"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">أبنائي</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="arhive-it__parents" type="checkbox"
                                                                          name="arhive-it" value="parents"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--parents"
                                        for="arhive-it__parents"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">والديَّ</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="arhive-it__friends" type="checkbox"
                                                                          name="arhive-it" value="friends"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--friends"
                                        for="arhive-it__friends"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">صديقي</div>
                            <div class="form-input__checkbox-label">المقرّب</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="arhive-it__b_s" type="checkbox"
                                                                          name="arhive-it" value="b_s"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--b_s"
                                        for="arhive-it__b_s"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">أخواني أو/و</div>
                            <div class="form-input__checkbox-label">أخواتي</div>
                        </div>
                        <div class="form-input__checkbox-container form-input__checkbox-container--with-hight-paddins pc-hidden">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="arhive-it__b_c_m" type="checkbox"
                                                                          name="arhive-it" value="b_c"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--b_c"
                                        for="arhive-it__b_c_m"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">مديري و</div>
                            <div class="form-input__checkbox-label">زملائي</div>
                        </div>
                    </div>
                    <div class="form-input__checkbox-area form-input__checkbox-area--full form-input__checkbox-area--center">
                        <div class="form-input__checkbox-container form-input__checkbox-container--with-hight-paddins mobile-hidden">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="arhive-it__b_c" type="checkbox"
                                                                          name="arhive-it" value="b_c"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--b_c"
                                        for="arhive-it__b_c"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">مديري و</div>
                            <div class="form-input__checkbox-label">زملائي</div>
                        </div>
                        <div class="form-input__checkbox-container form-input__checkbox-container--with-hight-paddins">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="arhive-it__nothing" type="checkbox"
                                                                          name="arhive-it" value="nothing"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--nothing"
                                        for="arhive-it__nothing"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">لا شيء</div>
                            <div class="form-input__checkbox-label">من هذا</div>
                        </div>
                    </div>
                </div>
                <div class="form-body__row form-input"><input class="form-input__submit" type="submit" value="التالي">
                </div>
            </div>
        </form>
        <form class="form form--fifth-page" novalidate="novalidate">
            <div class="form__header form-header form-header--fifth-page">
                <div class="form-header__title-container">
                    <div class="form-header__title">كل شيء هنا عنك</div>
                    <div class="form-header__part">(الجزء 5 من 5)</div>
                </div>
                <div class="form-header__description">من الذي سيلهم تحول لياقتك؟</div>
                <div class="form-header__progress-bar">
                    <div class="form-header__progress form-header__progress--100"></div>
                </div>
            </div>
            <div class="form__body form-body">
                <div class="form-body__row form-input form-input--subtitle">
                    <div class="form-input__subtitle">لماذا تريد القيام بتمارين<br/> التحفيز الكهربائي العضلي؟ (اختر كل
                        ما ينطبق)
                    </div>
                </div>
                <div class="form-body__row form-input form-input--column">
                    <div class="form-input__checkbox-area form-input__checkbox-area--full">
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="do-want__illness-free" type="checkbox"
                                                                          name="do-want" value="illness-free"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--illness-free"
                                        for="do-want__illness-free"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">لأتعافى من</div>
                            <div class="form-input__checkbox-label">الأمراض</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="do-want__fitting-jeans" type="checkbox"
                                                                          name="do-want" value="fitting-jeans"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--fitting-jeans"
                                        for="do-want__fitting-jeans"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">لجعل جسمي</div>
                            <div class="form-input__checkbox-label">يلائم ملابسي</div>
                            <div class="form-input__checkbox-label">القديمة</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="do-want__having-more" type="checkbox"
                                                                          name="do-want" value="having-more"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--having-more"
                                        for="do-want__having-more"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">ليكون لدي المزيد
                                من
                            </div>
                            <div class="form-input__checkbox-label">الطاقة والقدرة على</div>
                            <div class="form-input__checkbox-label">التحمل والصلابة</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="do-want__avoiding-muscle" type="checkbox"
                                                                          name="do-want" value="avoiding-muscle"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--avoiding-muscle"
                                        for="do-want__avoiding-muscle"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">لتجنب آلام</div>
                            <div class="form-input__checkbox-label">العضلات</div>
                            <div class="form-input__checkbox-label">والمفاصل</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="do-want__feeling-young" type="checkbox"
                                                                          name="do-want" value="feeling-young"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--feeling-young"
                                        for="do-want__feeling-young"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">لأشعر بحيوية</div>
                            <div class="form-input__checkbox-label">الشباب</div>
                        </div>
                        <div class="form-input__checkbox-container pc-hidden">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="do-want__keep-mind_m" type="checkbox"
                                                                          name="do-want" value="keep-mind"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--keep-mind"
                                        for="do-want__keep-mind_m"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">لأحافظ على</div>
                            <div class="form-input__checkbox-label">ذكائي وتركيزي</div>
                        </div>
                    </div>
                    <div class="form-input__checkbox-area form-input__checkbox-area--full">
                        <div class="form-input__checkbox-container mobile-hidden">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="do-want__keep-mind" type="checkbox"
                                                                          name="do-want" value="keep-mind"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--keep-mind"
                                        for="do-want__keep-mind"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">لأحافظ على</div>
                            <div class="form-input__checkbox-label">ذكائي وتركيزي</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="do-want__more-active" type="checkbox"
                                                                          name="do-want" value="more-active"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--more-active"
                                        for="do-want__more-active"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">ليكون لدي حياة
                            </div>
                            <div class="form-input__checkbox-label">اجتماعية أكثر نشاطًا</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="do-want__stay-healthy" type="checkbox"
                                                                          name="do-want" value="stay-healthy"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--stay-healthy"
                                        for="do-want__stay-healthy"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">للمحافظة على</div>
                            <div class="form-input__checkbox-label">صحتي لأجل</div>
                            <div class="form-input__checkbox-label">عائلتي</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="do-want__beach-body" type="checkbox"
                                                                          name="do-want" value="beach-body"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--beach-body"
                                        for="do-want__beach-body"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">للحصول على</div>
                            <div class="form-input__checkbox-label">جسم رشيق</div>
                            <div class="form-input__checkbox-label">ومفتول العضلات</div>
                        </div>
                        <div class="form-input__checkbox-container">
                            <div class="form-input__checkbox-item"><input class="form-input__checkbox"
                                                                          id="do-want__impress-partner" type="checkbox"
                                                                          name="do-want" value="impress-partner"
                                                                          required="required"><label
                                        class="form-input__checkbox-checker form-input__checkbox-checker--impress-partner"
                                        for="do-want__impress-partner"></label></div>
                            <div class="form-input__checkbox-label form-input__checkbox-label--first">لأنال إعجاب
                                رفيقي/رفيقتي
                            </div><!--.form-input__checkbox-label رفيقي/ رفيقت--></div>
                    </div>
                </div>
                <div class="form-body__row form-input"><input class="form-input__submit" type="submit" value="التالي">
                </div>
            </div>
        </form>
        <form class="form form--final-page" novalidate="novalidate">
            <div class="form__header form-header form-header--final-page"></div>
            <div class="form__body form-body">
                <div class="form-body__row form-regrads">
                    <div class="form-regrads__title">شكرا لك على مشاركتك بالاستبيان</div>
                    <div class="form-regrads__description">لقد تلقينا ردودك وسوف يتلقى مدربك الشخصي معلوماتك
                        <div class="form-regrads__description-new_line"></div>
                        سنقوم بالتحليل وتخصيص برنامج لك لتحقيق النتائج
                    </div>
                </div>
                <div class="form-body__row social-links"><a class="social-links__link"
                                                            href="https://www.facebook.com/Nexfitkuwait/"><img
                                class="social-links__image" src="{{asset('/img/questionnaire/icons/6/fb-black.svg')}}"></a>
                    <!--a.social-links__link(href="https://www.youtube.com/channel/UChtt74nsVh9UYajuw1AdLMA")-->
                    <!--    img.social-links__image(src="img/icons/6/youtube-black.svg")-->
                    <a class="social-links__link"
                                                                                              href="https://www.instagram.com/nexfitkw/"><img
                                class="social-links__image" src="{{asset('/img/questionnaire/icons/6/inst-black.svg')}}"></a>
                </div>
            </div>
        </form>
    </main>
</div>
<script type="text/javascript" src="{{asset('/js/questionnaire/ar/bundle.js')}}"></script>
</body>
