import { users } from "./users";
import { rightArrow } from "./rightArrow";
import { search } from "./search";

export const getIcon = (iconsName) => {
    let icon;
    switch (iconsName) {
        case "users":
            icon = users;
            break;
        case "rightArrow":
            icon = rightArrow;
            break;
        case "search":
            icon = search;
            break;
        default:
            null;
    }
    return icon;
};
