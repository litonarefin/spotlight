export function stringCount(string, limit) {
    const words = string.split("");

    let result = words.slice(0, limit).join("");

    if (words.length > limit) {
        result += "...";
    }

    return result;
}
