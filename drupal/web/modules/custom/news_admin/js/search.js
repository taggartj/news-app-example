(function ($, Drupal, once) {
    Drupal.behaviors.newsSerch = {
        attach: function (context, settings) {
          const resultsWrapper = $('#savedResults');
          const searchEle = $('#edit-search');

          once('newsSerch', 'html', context).forEach(function (element) {
             setupFunction();
          });



          // @todo could make a controller to save for real.
          var favorites = [];
          function setupFunction() {

            $('body').on('click', '.save_result', function (event) {
              var replacedVal = searchEle.val();
              console.log(replacedVal);

              event.stopPropagation();
              event.preventDefault();
              var info = $(this).data();
              favorites.push(info);
              favoritesChange();
            });

            $('body').on('click', '.remove', function (event) {
              event.preventDefault();
              event.stopPropagation();
              var index = $(this).data('index');
              favorites.splice(index, 1);
              favoritesChange();
            });


            // thank you my self.
            //https://stackoverflow.com/a/48896071/4775955
            var check_favs = localStorage.getItem('favorites');
            if (check_favs && check_favs.length != 0) {
              favorites = JSON.parse(check_favs);
              favoritesChange();
            }

          };


          function favoritesChange() {
            console.log(favorites);
            localStorage.setItem('favorites', JSON.stringify(favorites));
            resultsWrapper.html('');
            // i hate mixing markup and js.
            resultsWrapper.append('<table id="favs"><tbody></tbody></table>');
            if (favorites.length != 0) {
              for (var x in favorites) {
                var item = favorites[x];
                $("#favs > tbody").append("<tr><td>"+ item.title + "</td><td><a href='"+ item.url + "' target='_blank'>"+ item.url + "</a></td><td><button data-index='"+ x +"' class='remove'>Remove</button></td></tr>");
              }
            }
          }
        },
        };
  })(jQuery, Drupal, once);
