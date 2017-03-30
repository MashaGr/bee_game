$(document).ready(function() {

    // Push start button
    $('body').on('click', '#start_game_button', function () {
        startGame();
    });

    // Push stop button
    $('body').on('click', '#stop_game_button', function () {
        stopGame();
    });

    // Push hit button (hitting random alive bee)
    $('body').on('click', '#hit_bee_button', function () {
        beeGotHit('random', false);
    });

    // Hit alive bee via clicking on img
    $('body').on('click', '.col-md-1:not(.col-bee-white, .col-bee-dead)', function () {
        var objectId = $(this).data('bee-id');
        beeGotHit(objectId, $(this));
    });

});


var startGame = function() {
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'game/start-game',
        success: function(response) {
            if (response && (response.result === 'success')) {
                $('body').find('.col-md-1').remove();
                createStopNavBlock();

                $.each(response.objects, function(key, value) {
                    var emptyDiv = $("<div/>").addClass("col-md-1 col-bee-white");
                    var newBeeDiv = $("<div/>")
                        .attr("data-bee-id", value.id)
                        .addClass("col-md-1 col-bee-" + value.object.beeType)
                        .html("<h5>"+value.object.currentLifespan+"</h5>");
                    $(".wrapper .row").append(emptyDiv).append(newBeeDiv);
                });
            }
        }
    });
};


var stopGame = function() {
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'game/stop-game',
        success: function(response) {
            if (response && (response.result === 'success')) {
                createStartButton();
                $('body').find('.col-md-1').remove();
            }
        }
    });
};


var beeGotHit = function(objectId, el) {
    $.ajax({
        type: 'POST',
        dataType: 'JSON',
        url: 'game/bee-got-hit',
        data: {
            object_id: objectId
        },
        success: function(response) {
            if (response && (response.result === 'success')) {
                // If we hit random bee, find it by id
                if (objectId === 'random') {
                    el = $('body').find('[data-bee-id="' + response.bee.id + '"]');
                }
                // Update alive bee's lifespan
                if (response.upd_lifespan > 0) {
                    el.find('h5').text(response.upd_lifespan);
                // Update dead bee's lifespan (not queen)
                } else if (response.dead_amount === 'one') {
                    el.addClass('col-bee-dead');
                    el.find('h5').remove();
                // Make all bees dead (because queen is dead). Happy end.
                } else {
                    $('body').find('.col-bee-queen, .col-bee-worker, .col-bee-drone').addClass('col-bee-dead');
                    $('body').find('.col-bee-queen, .col-bee-worker, .col-bee-drone').find('h5').remove();
                    createStartButton();
                }
            }
        }
    });
};


var createStopNavBlock = function () {
    $('#start_game_button').remove();
    var stopNavBlock= $("<div/>")
        .addClass("nav-stop-block")
        .html('<button id="stop_game_button" class="btn btn-danger">Stop</button>' +
            '<button id="hit_bee_button" class="btn btn-warning">Hit</button>');
    $(".container").prepend(stopNavBlock);
};


var createStartButton = function () {
    $('.nav-stop-block').remove();
    var startButton = $("<button/>")
        .attr("id", "start_game_button")
        .addClass("btn btn-success")
        .text("Start");
    $(".container").prepend(startButton);
};

