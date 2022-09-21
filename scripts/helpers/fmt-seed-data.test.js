// noinspection DuplicatedCode

/**
 * @name NumberLong
 * @param {string} intAsStr
 * @returns {number}
 */
function NumberLong(intAsStr) {
    return Number(intAsStr);
}
// prettier-ignore
let data = [{"filename":"0100ABF008968000","filesize":NumberLong("15971909632"),"filetype":"xci"},{"filename":"01008DB008C2C000","filesize":NumberLong("15971909632"),"filetype":"xci"},{"filename":"010018E011D92000","filesize":NumberLong("7985954816"),"filetype":"xci"},{"filename":"0100000011D90000","filesize":NumberLong("4997734912"),"filetype":"xci"},{"filename":"010003F003A34000","filesize":NumberLong("4851431936"),"filetype":"xci"},{"filename":"0100187003A36000","filesize":NumberLong("4468491264"),"filetype":"xci"},{"filename":"00040000001B5000_v00","filesize":NumberLong("4294967295"),"filetype":"3ds"},{"filename":"00040000001B5100_v00","filesize":NumberLong("4294967295"),"filetype":"3ds"},{"filename":"0004000000175E00_v00","filesize":NumberLong("4294967295"),"filetype":"3ds"},{"filename":"0004000000164800_v00","filesize":NumberLong("4294967295"),"filetype":"3ds"},{"filename":"000400000011C400_v00","filesize":NumberLong("2147483648"),"filetype":"3ds"},{"filename":"000400000011C500_v00","filesize":NumberLong("2147483648"),"filetype":"3ds"},{"filename":"0004000000055D00_v00","filesize":NumberLong("2147483648"),"filetype":"3ds"},{"filename":"0004000000055E00_v00","filesize":NumberLong("2147483648"),"filetype":"3ds"},{"filename":"0004000000174600","filesize":NumberLong("2147483648"),"filetype":"3ds"},{"filename":"POKEMON_B2_IREO01","filesize":536870912,"filetype":"nds"},{"filename":"POKEMON_W2_IRDO01","filesize":536870912,"filetype":"nds"},{"filename":"POKEMON_B_IRBO01","filesize":268435456,"filetype":"nds"},{"filename":"POKEMON_W_IRAO01","filesize":268435456,"filetype":"nds"},{"filename":"POKEMON_HG_IPKE01","filesize":134217728,"filetype":"nds"},{"filename":"POKEMON_SS_IPGE01","filesize":134217728,"filetype":"nds"},{"filename":"POKEMON_PL_CPUE01","filesize":134217728,"filetype":"nds"},{"filename":"POKEMON_D_ADAE01","filesize":67108864,"filetype":"nds"},{"filename":"POKEMON_P_APAE","filesize":67108864,"filetype":"nds"},{"filename":"POKEMON_EMERBPEE01","filesize":16777216,"filetype":"gba"},{"filename":"POKEMON_FIREBPRE01","filesize":16777216,"filetype":"gba"},{"filename":"POKEMON_LEAFBPGE01","filesize":16777216,"filetype":"gba"},{"filename":"POKEMON_RUBYAXVE01","filesize":16777216,"filetype":"gba"},{"filename":"POKEMON_SAPPAXPE01","filesize":16777216,"filetype":"gba"},{"filename":"PM_CRYSTAL_BYTE01","filesize":2097152,"filetype":"gbc"},{"filename":"POKEMON_GLDAAUE01","filesize":2097152,"filetype":"gbc"},{"filename":"POKEMON_SLVAAXE01","filesize":2097152,"filetype":"gbc"},{"filename":"POKEMON_YELLOW01","filesize":1048576,"filetype":"gb"},{"filename":"POKEMON_GREEN01","filesize":1048576,"filetype":"gb"},{"filename":"POKEMON_BLUE01","filesize":1048576,"filetype":"gb"},{"filename":"POKEMON_RED01","filesize":1048576,"filetype":"gb"},{"filename":"pokeprism","filesize":2097152,"filetype":"gbc"},{"filename":"pokemon_brown_2014-red_hack","filesize":2097152,"filetype":"gb"},{"filename":"genesis-final-2019-08-23","filesize":16777216,"filetype":"gba"},{"filename":"Pokemon_Ash_Gray_4-5-3","filesize":16777216,"filetype":"gba"},{"filename":"RenegadePlatinum","filesize":104923028,"filetype":"nds"},{"filename":"01001F5010DFA000","filesize":NumberLong("7985954816"),"filetype":"xci"}];

const TWO_GIBIBYTES = 0x80000000; /*2147483648*/

data.forEach((seed) => {
    if (seed.filesize >= TWO_GIBIBYTES)
        console.log(seed.filename, parseInt(seed.filesize, 10));
});
