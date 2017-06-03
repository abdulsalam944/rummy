      /* ---- Shuffle ------- */

          function shuffle(array) {
              var currentIndex = array.length, temporaryValue, randomIndex;

              // While there remain elements to shuffle...
              while (0 !== currentIndex) {

                // Pick a remaining element...
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex -= 1;

                // And swap it with the current element.
                temporaryValue = array[currentIndex];
                array[currentIndex] = array[randomIndex];
                array[randomIndex] = temporaryValue;
              }

           
            return array;
          }


          /** =========== Meld Functions ============== **/


        // check that values match and suits are unique
        function isSameValue(cards){
          var check = _isSameValue(cards, false);
          
          // if the non-wildcards matched, just assume any wildcards are also matches,
          // otherwise, pull the fake jokers and try again
          if(check.isSameValue) {
            return check;
          }
          
          check = _isSameValue(cards, true);
          
          if(!check.isSameValue) {
            check.score = undefined;
          }
          
          return check;
        }

        function _isSameValue(cards, useJokers) {
          var suits = ["clubs","hearts","spades","diams"];
          var jokers = [];
          var remainder = [];
          cards.forEach(function(card){
            ((card.suit === "joker" || (useJokers && card.value === jokerValue)) ? jokers : remainder).push(card);
          });
          if(useJokers) {
          }
          var score = 0;
          var pure = jokers.length === 0;
          var value;
          var matched = remainder.every(function(card){
            if(value) {
              if(value != card.value) {
                return false;
              }
              var suit = suits.indexOf(card.suit);
              if(suit < 0) {
                return false;
              }
              suits.splice(suit,1);
              score += (card.value > 1 && card.value < 10) ? card.value : 10;
              return true;
            }
            value = card.value;
            score += (card.value > 1 && card.value < 10) ? card.value : 10;
            return true;
          });
          return {isPure: pure, isSameValue: matched, score: score};
        }

        // check that suits match; values are unchecked
        function isSameSuit(cards){
          var check = _isSameSuit(cards, false);
          
          if(check.isSameSuit) {
            return check;
          }
          
          check = _isSameSuit(cards, true);
          
          return check;
        }

        function _isSameSuit(cards, useJokers) {
          var jokers = [];
          var remainder = [];
          cards.forEach(function(card){
            (card.suit === "joker" ? jokers : (useJokers && card.value === jokerValue ? jokers : remainder)).push(card);
          });
          var pure = jokers.length === 0;
          var suit;
          var sameSuit = remainder.every(function(card){
            if(!suit){
              suit = card.suit;
              return true;
            }
            return card.suit == suit;
          });
          return {isPure: pure, isSameSuit: sameSuit};
        }

        // check for sequence; suits must match
        function isSequence(cards){
          var sameSuit = isSameSuit(cards);
          if(!sameSuit.isSameSuit) {
            return {isPure:undefined, isSequence: false, score: undefined};
          }
          
          var check = _isSequence(cards, sameSuit, false);
          
          if(check.isSequence) {
            return check;
          }
          
          check = _isSequence(cards, sameSuit, true);
          
          return check;
        }

        function _isSequence(cards, sameSuit, useJokers) {
          var score = 0;
          
          var jokers = [];
          var remainder = cards.filter(function(card){
            if(card.suit === "joker" || (useJokers && card.value === jokerValue)) {
              jokers.push(card);
              return false;
            }
            return true;
          });
          var pure = jokers.length === 0;
          
          if(remainder.length <= 1) {
            return {isPure: false, isSequence: true, score: 0};
          }

          // Sort the non-jokers
          remainder = remainder.sort(function(a, b){
            return a.value - b.value;
          });
          
          // find a sequence
          // rotate through each card to start and count through them, filling in with available jokers as necessary
          for(var i = 0; i < remainder.length; i++){
            var jokerCount = jokers.length;
            var lastValue = remainder[0].value;
            score = (lastValue > 1 && lastValue < 10) ? lastValue: 10;
            var fail = false;
            for(var j = 1; j < remainder.length; j++){
              // aces at the beginning or end, but not in the middle
              if(lastValue >= 14) {
                fail = true;
                break;
              }
              if(lastValue + 1 == remainder[j].value) {
                lastValue++;
                score += (lastValue > 1 && lastValue < 10) ? lastValue: 10;
                continue;
              }
              if(lastValue == 13 && remainder[j].value == 1) {
                lastValue++;
                score += (lastValue > 1 && lastValue < 10) ? lastValue: 10;
                continue;
              }
              if(jokerCount > 0) {
                jokerCount--;
                j--;
                lastValue++;
                score += (lastValue > 1 && lastValue < 10) ? lastValue: 10;
                continue;
              }
              fail = true;
              break;
            }
            // Did we make it through a complete sequence?
            if(!fail){
              return {isPure: pure, isSequence: true, score: score };
            }
            // move bottom card to top and try again
            remainder.unshift(remainder.pop());
          }
          
          return {isPure: undefined, isSequence: false, score: undefined};
        }

        function getSummary(cards, i){
          if(cards.length != 3 && cards.length != 4) {
            // invalid number of cards, return undefined
            return;
          }
          
          var long = cards.length == 4;
          var sameValue = isSameValue(cards);
          var sequence = isSequence(cards);
          var pure = (sameValue.isSameValue && sameValue.isPure) || (sequence.isSequence && sequence.isPure);
          
     /*     return JSON.stringify({
            "cards": cards,
            "isLong": long, 
            "isPure": pure, 
            "isSameValue": sameValue.isSameValue, 
            "isSequence": sequence.isSequence,
            "sameValueScore": sameValue.score,
            "sequenceScore": sequence.score
          });*/

           eval('meldCardEvaluator'+i).push({"cards": cards, "isLong": long, "isPure": pure, "isSameValue": sameValue.isSameValue, "isSequence": sequence.isSequence, "sameValueScore": sameValue.score, "sequenceScore": sequence.score });

           return JSON.stringify( eval(' meldCardEvaluator'+i));
        }


     

        /*function toString(cards) {
          var results = [];
          cards.forEach(function(card){
            if(card.suit === "joker") {
              results.push("JK");
            }
            else {
              var value;
              switch (card.value) {
                case  1: value = "A"; break;
                case 11: value = "J"; break;
                case 12: value = "Q"; break;
                case 13: value = "K"; break;
                default:
                  value = card.value;
                  break;
              }
              results.push(value+card.suit.charAt(0));
            }
          });
          return results.join(' ');
        }

        function handScore(cards) {
          return cards.reduce(function(score, card){
            return score + (card.suit === "joker" ? 0 : ((card.value > 1 && card.value < 10) ? card.value : 10));
          }, 0);
        }*/


          /* show select */

           


            /*  Get next greater player function */

            function getItem(array, search) {
                return array.reduce(function (r, a) {
                    return a > search && (!r || r > a) ? a : r;
                }, undefined);
            }

            function getItem_prev(array, search) {
                var length = array.length;
                var index = array.indexOf(search);
                var prevUser;
                if(index==0){
                  prevUser = array[length-1];
                }else{
                  prevUser = array[--index];
                }
                return prevUser;
            }