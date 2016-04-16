window.homeResize = ->
    windowHeight = $(window).height()
    windowWidth = $(window).width()
    highlight = $('#highlight')

    $('#screen').height windowHeight

    if windowWidth >= 520 and windowHeight >= 350
        highlight.css
            top:  (windowHeight + 50 - highlight.height()) / 2
            left: (windowWidth - highlight.width()) / 2
        $('.mask').attr 'cy', (windowHeight + 50 - $('.mask').height() ) / 2
    else
        highlight.css
            top:  65 #50+15
            left: 15
    return

window.articleResize = ->
    windowHeight = $(window).height()
    windowWidth = $(window).width()
    parallax = $('.parallax-window')
    spot = $('.spot')

    parallax.css
        'min-height': windowHeight

    if windowWidth >= 520
        spot.css
            top:  (parallax.height() + 50 - spot.height()) / 2
            left: (windowWidth - spot.width()) / 2
    else
        spot.css
            top:  65 #50+15
            left: 15

    return

$('.loading').hide()
$('.cloak').show()