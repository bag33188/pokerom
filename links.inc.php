<?php
echo /** @lang HTML */
<<<'EOT'
<!--<li><a href="https://www.pokemon.com/us/strategy/the-details-of-discovering-pokemon-eggs">The Details of Discovering Pokémon Eggs</a></li>-->
<!--<li><a href="https://www.pokemon.com/us/strategy/raising-battle-ready-pokemon/">Raising Battle-Ready Pokémon!</a></li>-->
<!--<li><a href="https://www.pokemon.com/us/pokemon-video-games/pokemon-ultra-sun-and-pokemon-ultra-moon/">Pokémon Ultra Sun and Pokémon Ultra Moon</a></li>-->
<!--<li><a href="https://www.pokemon.com/us/pokemon-video-games/all-pokemon-games/">All Pokémon Video Games</a></li>-->
<!--<li><a href="https://www.pokemon.com/us/pokemon-video-games/pokemon-emerald-version/">Pokémon Emerald Version</a></li>-->
<!--<li><a href="https://www.pokemon.com/us/pokemon-video-games/pokemon-platinum-version/">Pokémon Platinum Version</a></li>-->
<!--<li><a href="https://projectpokemon.org/home/tutorials/save-editing/using-pkhex/">Using PKHeX</a></li>-->
EOT;

$anchors = array(
    [
        'href' => 'https://www.pokemon.com/us/strategy/the-details-of-discovering-pokemon-eggs',
        'text' => 'The Details of Discovering Pokémon Eggs'
    ],
    [
        'href' => 'https://www.pokemon.com/us/strategy/raising-battle-ready-pokemon/',
        'text' => 'Raising Battle-Ready Pokémon!'
    ],
    [
        'href' => 'https://www.pokemon.com/us/pokemon-video-games/pokemon-ultra-sun-and-pokemon-ultra-moon/',
        'text' => 'Pokémon Ultra Sun and Pokémon Ultra Moon'
    ],
    [
        'href' => 'https://www.pokemon.com/us/pokemon-video-games/all-pokemon-games/',
        'text' => 'All Pokémon Video Games'
    ],
    [
        'href' => 'https://www.pokemon.com/us/pokemon-video-games/pokemon-emerald-version/',
        'text' => 'Pokémon Emerald Version'
    ],
    [
        'href' => 'https://www.pokemon.com/us/pokemon-video-games/pokemon-platinum-version/',
        'text' => 'Pokémon Platinum Version'
    ],
    [
        'href' => 'https://projectpokemon.org/home/tutorials/save-editing/using-pkhex/',
        'text' => 'Using PKHeX'
    ],
);
?>
<?php foreach ($anchors as $anchor): ?>
    <li><a href="<?= $anchor['href']; ?>"><?= $anchor['text']; ?></a></li>
<?php endforeach; ?>
