/**
 * Space Character Unicode Entity
 *
 * @name _SPACE_
 * @const
 * @type {string}
 * @default \u0020
 */
const _SPACE_ = "\u0020";

/**
 * @summary Capitalizes a string
 * @description By default this method capitalizes only the first word in a string.
 * @param {CapitalizationOptionsObject} options
 * @returns {string|null} capitalized string
 */
String.prototype.capitalize = function (
    options = {
        deep: false,
        depth: 0,
        separator: _SPACE_,
    }
) {
    let {deep, separator, depth} = options;
    let strVal = this.trim();
    if (!strVal.length) return null;
    let strArr = strVal.split(separator);
    if (deep === true && depth > 0) {
        let strWordCount = strArr.length;
        if (depth > strWordCount) depth = strWordCount;
        let i = 0;
        do {
            strArr[i] =
                strArr[i][0].toUpperCase() + strArr[i].slice(1).toLowerCase();
            i++;
        } while (i < depth);
        return strArr.join(separator);
    } else if (deep === true && depth === 0) {
        return strArr
            .map((word) => word[0].toUpperCase() + word.slice(1).toLowerCase())
            .join(separator);
    } else {
        return strVal[0].valueOf().toUpperCase() + strVal.slice(1).valueOf().toLowerCase();
    }
};

/**
 * @summary Capitalization Options Object
 * @description Use this ES5 function/class to make a new options object for the **capitalize** function.
 * @param {boolean} [deep] whether to capitalize all words in the string or not (defaults to false).
 * @param {string} [separator] Set to any string value if you wish to distinguish the words in your string by a character other than space (default: space char)
 * @param {number} [depth] amount of words to capitalize in string
 * @returns {CapitalizationOptionsObject}
 * @constructor
 */
let CapitalizationOptions = function (
    deep = false,
    depth = 0,
    separator = _SPACE_
) {
    this.deep = deep;
    this.depth = depth;
    this.separator = separator;
    return {
        deep: this.deep,
        depth: this.depth,
        separator: this.separator,
    };
};

// globalize/export prototype class
window.String.prototype.capitalize = String.prototype.capitalize;

// globalize/export options class-object
window.CapitalizationOptions = CapitalizationOptions;
