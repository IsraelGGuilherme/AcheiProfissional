<?php

/**
 * Funções de apresentação usadas nas telas de busca, perfil público e perfil privado.
 * Carregue com: helper('profissional');
 */

if (!function_exists('iniciais_nome')) {
    /** "João da Silva" => "JS" */
    function iniciais_nome(?string $nome): string
    {
        $partes = preg_split('/\s+/', trim((string) $nome), -1, PREG_SPLIT_NO_EMPTY);

        if (empty($partes)) {
            return '?';
        }

        $primeira = mb_substr($partes[0], 0, 1);
        $ultima   = count($partes) > 1 ? mb_substr(end($partes), 0, 1) : mb_substr($partes[0], 1, 1);

        return mb_strtoupper($primeira . $ultima);
    }
}

if (!function_exists('formata_media')) {
    /** 4.8 => "4,8"; sem avaliações => "-" */
    function formata_media($media): string
    {
        if ($media === null || $media === '') {
            return '-';
        }

        return number_format((float) $media, 1, ',', '.');
    }
}

if (!function_exists('estrelas_texto')) {
    /** 4.6 => "★★★★★"; 3.2 => "★★★☆☆" */
    function estrelas_texto($media): string
    {
        $cheias = (int) round((float) $media);
        $cheias = max(0, min(5, $cheias));

        return str_repeat('★', $cheias) . str_repeat('☆', 5 - $cheias);
    }
}

if (!function_exists('rotulo_avaliacoes')) {
    function rotulo_avaliacoes(int $total): string
    {
        if ($total === 0) {
            return 'Sem avaliações';
        }

        return $total . ($total === 1 ? ' avaliação' : ' avaliações');
    }
}

if (!function_exists('link_whatsapp')) {
    /** Monta o link wa.me aceitando números com ou sem DDI. Retorna null se inválido. */
    function link_whatsapp(?string $numero): ?string
    {
        $digitos = preg_replace('/\D/', '', (string) $numero);

        if ($digitos === '' || strlen($digitos) < 10) {
            return null;
        }

        if (strlen($digitos) <= 11) {
            $digitos = '55' . $digitos;
        }

        return 'https://wa.me/' . $digitos;
    }
}

if (!function_exists('formata_telefone')) {
    function formata_telefone(?string $numero): string
    {
        $d = preg_replace('/\D/', '', (string) $numero);

        if (strlen($d) === 11) {
            return sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 5), substr($d, 7));
        }

        if (strlen($d) === 10) {
            return sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 4), substr($d, 6));
        }

        return (string) $numero;
    }
}

if (!function_exists('formata_documento')) {
    /** CPF (11 dígitos) ou CNPJ (14 caracteres, numérico ou alfanumérico) com máscara. */
    function formata_documento(?string $doc): string
    {
        $d = strtoupper(preg_replace('/[^0-9A-Za-z]/', '', (string) $doc));

        if (strlen($d) === 11 && ctype_digit($d)) {
            return sprintf('%s.%s.%s-%s', substr($d, 0, 3), substr($d, 3, 3), substr($d, 6, 3), substr($d, 9));
        }

        if (strlen($d) === 14) {
            return sprintf('%s.%s.%s/%s-%s', substr($d, 0, 2), substr($d, 2, 3), substr($d, 5, 3), substr($d, 8, 4), substr($d, 12));
        }

        return (string) $doc;
    }
}

if (!function_exists('url_foto')) {
    /** Caminho relativo salvo no banco => URL pública. Retorna null se vazio. */
    function url_foto(?string $caminho): ?string
    {
        return $caminho ? base_url($caminho) : null;
    }
}

if (!function_exists('tempo_atras')) {
    /** "2026-10-01 10:00:00" => "há 3 dias" */
    function tempo_atras(?string $data): string
    {
        if (!$data) {
            return '';
        }

        $diff = time() - strtotime($data);

        if ($diff < 60) {
            return 'agora há pouco';
        }

        $unidades = [
            ['ano', 'anos', 31536000],
            ['mês', 'meses', 2592000],
            ['dia', 'dias', 86400],
            ['hora', 'horas', 3600],
            ['minuto', 'minutos', 60],
        ];

        foreach ($unidades as [$singular, $plural, $segundos]) {
            $n = intdiv($diff, $segundos);

            if ($n >= 1) {
                return 'há ' . $n . ' ' . ($n === 1 ? $singular : $plural);
            }
        }

        return '';
    }
}

if (!function_exists('dia_semana_rotulo')) {
    function dia_semana_rotulo(string $dia): string
    {
        return [
            'DOMINGO' => 'Domingo',
            'SEGUNDA' => 'Segunda-feira',
            'TERCA'   => 'Terça-feira',
            'QUARTA'  => 'Quarta-feira',
            'QUINTA'  => 'Quinta-feira',
            'SEXTA'   => 'Sexta-feira',
            'SABADO'  => 'Sábado',
        ][$dia] ?? $dia;
    }
}
