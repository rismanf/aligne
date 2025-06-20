<div>
     <div id="modal" style="background-color: rgb(221, 221, 221); visibility: hidden; top: 2rem; opacity: 0;"><a
            id="close" class="closeColor">
            <div class="icon"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                    style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:2">
                    <path style="fill:none" d="M0 0h24v24H0z"></path>
                    <path d="M18 6 6 18M6 6l12 12" style="fill:none;fill-rule:nonzero;stroke:#fff;stroke-width:2px">
                    </path>
                </svg></div>
        </a>
        <div id="qrView" class="textColor">
            <div id="qr"></div>
            <h3>Scan the QR Code</h3>
            <p>to view my Business Card on another device</p>
        </div>
    </div>
    <header>
        <div id="topActions" style="display: none;">
            <div><a id="share">
                    <div class="icon topAction"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                            xml:space="preserve"
                            style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-miterlimit:2">
                            <g>
                                <path style="fill:none" d="M0 0h24v24H0z"></path>
                                <clipPath id="a">
                                    <path d="M0 0h24v24H0z"></path>
                                </clipPath>
                                <g clip-path="url(#a)">
                                    <circle cx="17" cy="5" r="3"
                                        style="fill:none;stroke:#fff;stroke-width:2px"></circle>
                                    <circle cx="5" cy="12" r="3"
                                        style="fill:none;stroke:#fff;stroke-width:2px"></circle>
                                    <circle cx="17" cy="19" r="3"
                                        style="fill:none;stroke:#fff;stroke-width:2px"></circle>
                                    <path d="m7.59 13.51 6.83 3.98m-.01-10.98-6.82 3.98"
                                        style="fill:none;stroke:#fff;stroke-width:2px"></path>
                                </g>
                            </g>
                        </svg></div>
                </a> <a id="showQR">
                    <div class="icon topAction"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                            xml:space="preserve"
                            style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:2">
                            <path style="fill:none" d="M0 0h24v24H0z"></path>
                            <path d="M4 4h4v4H4V4Zm0 12h4v4H4v-4ZM16 4h4v4h-4V4Z"
                                style="fill:none;stroke:#fff;stroke-width:2px"></path>
                            <path d="M12 4v14c0 1.097.903 2 2 2h4c1.097 0 2-.903 2-2v-4c0-1.097-.903-2-2-2H4"
                                style="fill:none;stroke:#fff;stroke-width:2px;stroke-linejoin:miter"></path>
                            <path style="fill:#fff" d="M15 15h2v2h-2z"></path>
                        </svg></div>
                </a></div> <!---->
        </div>
        <div class="headerImgC">
            <img id="cover" src="{{ asset('assets/img/about-compo.webp') }}" alt="Background Pattern">
        </div>
    </header>
    <main style="background-color: rgb(221, 221, 221); margin-top: 0px;">
        @if ($data->foto)
            <img id="profilePhoto" src="{{ asset('asset/vcard/photos/' . $data->foto) }}" style="object-fit:cover">
        @endif
        <div id="info" class="textColor">
            <p class="name">
                {{ $data->name }}
            </p>
            @if ($data->nik == 2208052)
                <p class="name">
                    <img class="certificate" src="{{ asset('asset/vcard/certificate/certificate_deta_1.png') }}"
                        style="object-fit:cover">
                    <img class="certificate" src="{{ asset('asset/vcard/certificate/certificate_deta_2.png') }}"
                        style="object-fit:cover">
                    <img class="certificate" src="{{ asset('asset/vcard/certificate/certificate_deta_3.png') }}"
                        style="object-fit:cover">
                    <img class="certificate" src="{{ asset('asset/vcard/certificate/certificate_deta_4.png') }}"
                        style="object-fit:cover">
                    <img class="certificate" src="{{ asset('asset/vcard/certificate/certificate_deta_5.png') }}"
                        style="object-fit:cover">
                </p>
            @endif
            <p class="jobtitle">
                {{ $data->job }}
            </p>
            <p class="bizname">
                PT Telkom Data Ekosistem | NeutraDC
            </p>
            <p class="bizaddr">
                Telkom Landmark Tower, 5th floor, The Telkom Hub, Jl. Gatot Subroto Kav.52, Jakarta 12710, Indonesia
            </p>
        </div>
        <a id="cta" rel="noreferrer" download="" target="_blank" aria-label="Save Contact"
            style="background-color: rgb(186, 32, 37);" class="add-to-contact-btn">
            <div class="icon iconColor"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    xml:space="preserve"
                    style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:2">
                    <path style="fill:none" d="M0 0h24v24H0z"></path>
                    <circle cx="8.5" cy="7" r="4" style="fill:none;stroke:#fff;stroke-width:2.29px"
                        transform="matrix(.875 0 0 .875 4.563 -.625)"></circle>
                    <path d="M86 181c0-3.863 3.137-7 7-7s7 3.137 7 7" style="fill:none;stroke:#fff;stroke-width:2px"
                        transform="translate(-81 -163)"></path>
                    <path d="M104 168v6m3-3-3 3-3-3" style="fill:none;fill-rule:nonzero;stroke:#fff;stroke-width:2px"
                        transform="translate(-92 -152)"></path>
                </svg></div>
            <p class="iconColor">Save Contact</p>
        </a>
        <div class="actions">
            <div class="actionsC">
                <div class="actionBtn"><a href="tel:{{ $data->phone_number }}" target="_blank"
                        rel="noopener noreferrer" aria-label="Mobile" style="background-color: rgb(186, 32, 37);">
                        <div class="icon iconColor"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                xml:space="preserve"
                                style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:2">
                                <path style="fill:none" d="M0 0h24v24H0z"></path>
                                <path
                                    d="M11.5 22a.952.952 0 0 1-1.052.945c-4.358-.544-7.851-4.338-8.379-8.39a.938.938 0 0 1 .936-1.046c.399-.009.847-.009 1.202-.009.388 0 .738.237.882.597l.478 1.196a.95.95 0 0 1-.21 1.025l-.107.107a.951.951 0 0 0-.181 1.091c.495.825 1.59 1.92 2.425 2.396a.927.927 0 0 0 1.066-.177c.05-.02.086-.056.122-.092a.95.95 0 0 1 1.025-.21l1.196.478c.36.144.597.494.597.882V22Z"
                                    style="fill:none;stroke:#fff;stroke-width:.95px"
                                    transform="translate(-2.21 -26.421) scale(2.10526)"></path>
                            </svg></div>
                    </a>
                    <p class="textColor">
                        Mobile
                    </p>
                </div>
            </div>
            <div class="actionsC">
                <div class="actionBtn"><a href="mailto:{{ $data->email }}" target="_blank"
                        rel="noopener noreferrer" aria-label="Email" style="background-color: rgb(186, 32, 37);">
                        <div class="icon iconColor"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                xml:space="preserve"
                                style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:2">
                                <path style="fill:none" d="M0 0h24v24H0z"></path>
                                <path
                                    d="M22 33.75c0-.966-.896-1.75-2-1.75H4c-1.104 0-2 .784-2 1.75v10.5c0 .966.896 1.75 2 1.75h16c1.104 0 2-.784 2-1.75v-10.5Z"
                                    style="fill:none;stroke:#fff;stroke-width:1.86px"
                                    transform="matrix(1 0 0 1.14286 0 -32.571)"></path>
                                <path d="m18 7.042-6 2.625-6-2.625"
                                    style="fill:none;fill-rule:nonzero;stroke:#fff;stroke-width:1.86px"
                                    transform="matrix(1 0 0 1.14286 0 1.952)"></path>
                            </svg></div>
                    </a>
                    <p class="textColor">
                        Email
                    </p>
                </div>
            </div>
            <div class="actionsC">
                <div class="actionBtn"><a target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"
                        style="background-color: rgb(186, 32, 37);" href="https://wa.me/{{ $data->phone_number }}">
                        <div class="icon iconColor"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                xml:space="preserve"
                                style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:1.5">
                                <path style="fill:none" d="M0 0h24v24H0z"></path>
                                <path
                                    d="M5.43 4.93A9.969 9.969 0 0 1 12.5 2c5.519 0 10 4.481 10 10 0 2.76-1.12 5.26-2.93 7.07l.001.001-.003.001A9.97 9.97 0 0 1 12.5 22a9.835 9.835 0 0 1-5.005-1.354l-4.187.546.546-4.187A9.835 9.835 0 0 1 2.5 12a9.97 9.97 0 0 1 2.928-7.068l.001-.003.001.001ZM16.5 16c-4.415 0-8-3.585-8-8"
                                    style="fill:none;stroke:#fff;stroke-width:2px"></path>
                            </svg></div>
                    </a>
                    <p class="textColor">
                        WhatsApp
                    </p>
                </div>
            </div>
            <div class="actionsC">
                <div class="actionBtn"><a target="_blank" rel="noopener noreferrer" aria-label="Website"
                        style="background-color: rgb(186, 32, 37);" href="https://www.neutradc.com">
                        <div class="icon iconColor"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                xml:space="preserve"
                                style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:2">
                                <g>
                                    <path style="fill:none" d="M0 0h24v24H0z"></path>
                                    <clipPath id="a">
                                        <path d="M0 0h24v24H0z"></path>
                                    </clipPath>
                                    <g clip-path="url(#a)">
                                        <circle cx="12" cy="12" r="10"
                                            style="fill:none;stroke:#fff;stroke-width:2px"></circle>
                                        <ellipse cx="40" cy="40" rx="4" ry="10"
                                            style="fill:none;stroke:#fff;stroke-width:2px;stroke-linejoin:miter"
                                            transform="translate(-28 -28)"></ellipse>
                                        <path d="M40 50s-2-4-2-10 2-10 2-10"
                                            style="fill:none;stroke:#fff;stroke-width:2px"
                                            transform="rotate(-90 11.5 39.5)"></path>
                                    </g>
                                </g>
                            </svg></div>
                    </a>
                    <p class="textColor">
                        Website
                    </p>
                </div>
            </div>
        </div>
        <div class="actions secondary"></div>
        {{-- <div class="featured">
            <h2 class="section textColor">
                Section title
            </h2>
        </div> --}}
    </main>
    <footer class="textColor" style="background-color: rgb(221, 221, 221);">
        © {{ date('Y') }}
    </footer>
    <script src="{{ asset('asset/vcard/qrcode.min.js') }}"></script>

    <script>
        let m = document.getElementById("modal"),
            c = document.getElementById("close"),
            ki = document.getElementById("keyView"),
            cv = document.getElementById("copyView"),
            curl = document.getElementById("copyURL"),
            qrv = document.getElementById("qrView"),
            qr = document.getElementById("qr"),
            s = document.getElementById("share"),
            sqr = document.getElementById("showQR"),
            sk = document.getElementById("showKey");

        function tC(e) {
            "2rem" == e.style.top ? (e.style.visibility = "visible", e.style.top = "0px", e.style.opacity = 1) : (e.style
                .top = "2rem", e.style.opacity = 0, setTimeout(() => {
                    e.style.visibility = "hidden"
                }, 200))
        }

        function dN(e) {
            e.style.display = "none"
        }
        window.addEventListener("load", () => {
            document.querySelector("#topActions").style.display = "flex", qr.innerHTML = new QRCode({
                content: window.location.href,
                container: "svg-viewbox",
                join: !0,
                ecl: "L",
                padding: 0
            }).svg()
        }), navigator.canShare ? s.addEventListener("click", () => {
            navigator.share({
                title: document.title,
                text: "You can view my Digital Business Card here:",
                url: window.location.href
            })
        }) : s.addEventListener("click", () => {
            tC(m), cv.style.display = "flex", dN(qrv), ki && dN(ki)
        }), sqr.addEventListener("click", () => {
            tC(m), qrv.style.display = "block", dN(cv), ki && dN(ki)
        }), sk && sk.addEventListener("click", () => {
            tC(m), ki && (ki.style.display = "flex"), dN(cv), dN(qrv)
        }), c.addEventListener("click", () => tC(m)), curl.addEventListener("click", async () => {
            let e = curl.querySelectorAll(".iconColor")[1];
            await navigator.clipboard.writeText(window.location.href).then(t => {
                e.innerText = "Copied", setTimeout(() => {
                    e.innerText = "Copy URL"
                }, 1e3)
            })
        });
    </script>
    <script>
        let pC = document.querySelectorAll(".pCtrl"),
            pP = document.querySelectorAll(".playPause"),
            srcs = document.querySelectorAll(".source");
        srcs.forEach(e => {
            e.style.pointerEvents = "none", e.removeAttribute("controls")
        }), pC.forEach((e, l) => {
            e.style.display = "flex";
            let r = e.querySelector(".currentTime"),
                s = e.querySelector(".seekBar"),
                t = e.querySelector(".playPause"),
                a = t.querySelector(".play"),
                c = t.querySelector(".pause");
            srcs[l].addEventListener("timeupdate", () => {
                let e = srcs[l].currentTime,
                    t = 100 / srcs[l].duration * e;
                s.value = t, 100 == t && (s.value = 0, a.style.display = "block", c.style.display = "none");
                let o = Math.floor(e / 60),
                    y = Math.floor(e % 60);
                o.toString().length < 2 && (o = "0" + o), y.toString().length < 2 && (y = "0" + y), r
                    .value = o + ":" + y
            }), s.addEventListener("change", () => {
                srcs[l].currentTime = srcs[l].duration * (parseInt(s.value) / 100)
            }), t.addEventListener("click", () => {
                srcs[l].paused ? (srcs.forEach((e, r) => {
                    l != r && (e.paused || e.pause())
                }), pP.forEach((e, l) => {
                    let r = e.querySelector(".play"),
                        s = e.querySelector(".pause");
                    r.style.display = "block", s.style.display = "none"
                }), srcs[l].play(), a.style.display = "none", c.style.display = "block") : (srcs[l]
                    .pause(), c.style.display = "none", a.style.display = "block")
            })
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let VCF_CONTENT = 'BEGIN:VCARD\nVERSION:3.0';
        VCF_CONTENT += '\nFN:{{ $data->name }}';
        VCF_CONTENT += '\nN:{{ $data->name }}';
        VCF_CONTENT += '\nORG: PT Telkom Data Ekosistem';
        VCF_CONTENT += '\nADR;TYPE=WORK:Telkom Landmark Tower, 5th Floor, Jl. Gatot Subroto South Jakarta, Indonesia';
        VCF_CONTENT += '\nTITLE:{{ $data->job }}';
        VCF_CONTENT += '\nTEL;TYPE=CELL:{{ $data->phone_number }}';
        VCF_CONTENT += '\nTEL;TYPE=WORK:';
        VCF_CONTENT += '\nTEL;TYPE=HOME:';
        VCF_CONTENT += '\nTEL;TYPE=MSG:';
        VCF_CONTENT += '\nEMAIL;TYPE=WORK:{{ $data->email }}';
        VCF_CONTENT += '\nURL;TYPE=Digital Business Card:';
        VCF_CONTENT += '\nURL:https://www.neutradc.com';
        VCF_CONTENT += '\nURL;TYPE=WhatsApp:https://wa.me/{{ $data->phone_number }}';
        VCF_CONTENT += '\nURL;TYPE=Website:https://www.neutradc.com';
        VCF_CONTENT += '\nKEY;TYPE=PGP;ENCODING=b:';
        VCF_CONTENT += '\nNOTE:Data Center Company';
        VCF_CONTENT += '\nEND:VCARD';

        function download(filename, text) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/x-vcard;charset=utf-8,' + encodeURIComponent(text));
            element.setAttribute('download', filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);
        }
        $('.add-to-contact-btn').on('click', function() {
            // Start file download.
            download("{{ $data->name }}.vcf", VCF_CONTENT);
        });
    </script>
</div>
