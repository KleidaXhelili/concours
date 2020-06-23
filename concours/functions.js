$(function () {


    if ($('.confirm').length > 0) {

        $('.confirm').on('click', function () {
            return (confirm('Etes vous sûr(e) de vouloir supprimer ce candidat ?'));
        })

    }

});