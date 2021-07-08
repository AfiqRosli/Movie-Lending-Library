<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class MovieGenre extends Enum
{
    const Action = 'Action';
    const Adventure = 'Adventure';
    const Comedy = 'Comedy';
    const CrimeAndMystery = 'Crime & Mystery';
    const Fantasy = 'Fantasy';
    const Historical = 'Historical';
    const Horror = 'Horror';
    const Romance = 'Romance';
    const Satire = 'Satire';
    const ScienceFiction = 'Science Fiction';
    const Speculative = 'Speculative';
    const Thriller = 'Thriller';
    const Western = 'Western';
    const Other = 'Other';
}
