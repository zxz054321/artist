###*
# @param {string} body id="body"
# @param {string} bottom id="bottom"
###
window.fixBottom = (body = 'body', bottom = 'bottom') ->
    body = document.getElementById(body)
    bottom = document.getElementById(bottom)

    if !body or !bottom then return

    infoHeight = body.scrollHeight
    bottomHeight = bottom.scrollHeight
    allHeight = document.documentElement.clientHeight

    if infoHeight + bottomHeight < allHeight
        bottom.style.position = 'absolute'
        bottom.style.bottom = '0'
        return
    else
        bottom.style.position = ''
        bottom.style.bottom = ''
        return

window.getQueryString = (name) ->
    reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i')
    r = window.location.search.substr(1).match(reg)
    if r != null
        return unescape(r[2])
    null