<?php

/**
 * Hashcode 2022 pizza problem
 */


$FILES = [
  'a_an_example.in.txt',
  'b_basic.in.txt',
  'c_coarse.in.txt',
  'd_difficult.in.txt',
  'e_elaborate.in.txt',
];

$total_score = 0;
foreach ($FILES as $FILE) {
  $in = fopen($FILE, "r");
  $out_file = pathinfo($FILE, PATHINFO_FILENAME) . '.out';
  $out = fopen($out_file, 'w');

  $num_clients = read_line($in);

  $selected_ingredients = [];
  $ingredients = [];
  $liked_ingredients = [];
  $disliked_ingredients = [];
  $clients_liked = [];
  $clients_disliked = [];
  $file_score = 0;

  for ($i = 0; $i < $num_clients; $i++) {
    $liked = explode(' ', read_line($in));
    $liked_count = array_shift($liked);
    $liked_ingredients = array_merge($liked_ingredients, $liked);
    $clients_liked[$i] = $liked;
    $disliked = explode(' ', read_line($in));
    $disliked_count = array_shift($disliked);
    $disliked_ingredients = array_merge($disliked_ingredients, $disliked);
    $clients_disliked[$i] = $disliked;
  }
  fclose($in);

  $ingredients = array_unique(array_merge($liked_ingredients, $disliked_ingredients));

  // d($liked_ingredients);
  // d($disliked_ingredients);
  // d($ingredients);

  foreach ($ingredients as $ingredient) {
    $liked_occurrences = array_count_values_of($ingredient, $liked_ingredients);
    $disliked_occurrences = array_count_values_of($ingredient, $disliked_ingredients);
    if ($liked_occurrences >= $disliked_occurrences) {
      $selected_ingredients[] = $ingredient;
    }
  }

  // d('Selected:');
  // d($selected_ingredients);
  $output = count($selected_ingredients) . ' ' . implode(' ', $selected_ingredients);
  write_line($output, $out);
  fclose($out);

  $score = 0;

  for ($i = 0; $i < $num_clients; $i++) {
    $client_pass = 1;
    if (!empty(array_intersect($clients_disliked[$i], $selected_ingredients))) {
      $client_pass = 0;
    }
    if (!empty(array_diff($clients_liked[$i], $selected_ingredients))) {
      $client_pass = 0;
    }
    $score += $client_pass;
  }

  d("$FILE: " . $score);
  $total_score += $score;

}

d("TOTAL: " . $total_score);

function array_count_values_of($value, $array) {
  $counts = array_count_values($array);
  return isset($counts[$value]) ? $counts[$value] : 0;
}

function read_line($in_file) {
  return trim(fgets($in_file));
}

function write_line($text, $out_file) {
  fputs($out_file, $text . PHP_EOL);
}

/**
 * Prints input in an appropriate format for debugging.
 */
function d($output) {
  if (is_string($output)) {
    echo $output . "\n";
  } else {
    print_r($output);
  }
}
