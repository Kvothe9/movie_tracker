<?php
const TMDB_API_KEY = '30271a0f65393a8dde6d5115a1113980'; // 👈 Usa la API Key v3 auth, NO el token largo

function buscarPeliculaTMDB($titulo) {
    $titulo = urlencode($titulo);
    $url = "https://api.themoviedb.org/3/search/movie?api_key=" . TMDB_API_KEY . "&query=$titulo&language=es";

    $response = file_get_contents($url);
    if ($response === FALSE) return null;

    $data = json_decode($response, true);
    return $data['results'][0] ?? null;
}

function obtenerDetallesPelicula($movieId) {
    $url = "https://api.themoviedb.org/3/movie/$movieId?api_key=" . TMDB_API_KEY . "&language=es&append_to_response=credits";

    $response = file_get_contents($url);
    if ($response === FALSE) return null;

    return json_decode($response, true);
}

function buscarPeliculasTMDB($titulo) {
    $apiKey = '30271a0f65393a8dde6d5115a1113980'; // tu clave aquí
    $query = urlencode($titulo);
    $url = "https://api.themoviedb.org/3/search/movie?api_key=$apiKey&query=$query&language=es";

    $response = file_get_contents($url);
    if (!$response) return [];

    $data = json_decode($response, true);
    return $data['results'] ?? [];
}
