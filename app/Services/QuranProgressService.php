<?php

namespace App\Services;

class QuranProgressService
{
    // Total verses for Surah 1 to 114
    // Ref: https://en.wikipedia.org/wiki/List_of_surahs_in_the_Quran
    protected static $surahVerses = [
        0, // Index 0 padding
        7, 286, 200, 176, 120, 165, 206, 75, 129, 109,
        123, 111, 43, 52, 99, 128, 111, 110, 98, 135,
        112, 78, 118, 64, 77, 227, 93, 88, 69, 60,
        34, 30, 73, 54, 45, 83, 182, 88, 75, 85,
        54, 53, 89, 59, 37, 35, 38, 29, 18, 45,
        60, 49, 62, 55, 78, 96, 29, 22, 24, 13,
        14, 11, 11, 18, 12, 12, 30, 52, 52, 44,
        28, 28, 20, 56, 40, 31, 50, 40, 46, 42,
        29, 19, 36, 25, 22, 17, 19, 26, 30, 20,
        15, 21, 11, 8, 8, 19, 5, 8, 8, 11,
        11, 8, 3, 9, 5, 4, 7, 3, 6, 3,
        5, 4, 5, 6
    ];

    /**
     * Calculate total verses between Start(Surah,Ayat) and End(Surah,Ayat)
     */
    public static function calculateTotalAyat($startSurah, $startAyat, $endSurah, $endAyat)
    {
        // Simple validation
        if ($startSurah > $endSurah || ($startSurah == $endSurah && $startAyat > $endAyat)) {
            return 0; // Invalid range
        }

        // Case 1: Same Surah
        if ($startSurah == $endSurah) {
            return $endAyat - $startAyat + 1;
        }

        // Case 2: Cross Surah
        $total = 0;

        // 1. Ayat in start Surah (Total - Start + 1)
        $total += (self::$surahVerses[$startSurah] - $startAyat + 1);

        // 2. Full Surahs in between
        for ($i = $startSurah + 1; $i < $endSurah; $i++) {
            $total += self::$surahVerses[$i];
        }

        // 3. Ayat in end Surah
        $total += $endAyat;

        return $total;
    }
}
