window.writer = ->
    window.simplemde = new SimpleMDE
        element:                 $("#editor")[0]
        placeholder:'Enjoy writing.'
        autoDownloadFontAwesome: false
        spellChecker:false

angular.module('app', [])

fixBottom()