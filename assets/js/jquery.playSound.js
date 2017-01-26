/**
 * @author Alexander Manzyuk <admsev@gmail.com>
 * Copyright (c) 2012 Alexander Manzyuk - released under MIT License
 * https://github.com/admsev/jquery-play-sound
 * Usage: $.playSound('http://example.org/sound.mp3');
**/

(function($){

  $.extend({
    playSound: function(){
      return $("<audio id='shutter_sound' autoplay='autoplay' style='display:none;' controls='controls'><source src='"+arguments[0]+"' /></audio>").appendTo('body');
    }
  });

})(jQuery);
