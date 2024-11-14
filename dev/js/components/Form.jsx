import React, { useEffect } from "react";
import Users from "./Users";
import { useStore } from "../store/useStore";
import SearchList from "./SearchList";
const Fuse = require("fuse.js");
import { Toaster } from "react-hot-toast";

const Form = () => {
    const [fields, setStore] = useStore((store) => store);
    const { defaultData, searchText, selectedCategory } = fields;

    useEffect(() => {
        const fuseOptions = {
            // isCaseSensitive: false,
            // includeScore: false,
            // shouldSort: true,
            // includeMatches: false,
            // findAllMatches: false,
            // minMatchCharLength: 1,
            // location: 0,
            // threshold: 0.6,
            // distance: 100,
            // useExtendedSearch: false,
            useExtendedSearch: true,
            // ignoreLocation: false,
            // ignoreFieldNorm: false,
            // fieldNormWeight: 1,
            // keys: ["title", "author.firstName"],
            // ----- USER -----
            keys: ["title", "url", "slug", "data.title"],
        };

        const fuse = new Fuse(defaultData, fuseOptions);

        const result = fuse.search(searchText);
        setStore({ result: result });
    }, [searchText]);

    return (
        <div className="search-wrapper">
            {selectedCategory?.length === 0 ? <SearchList /> : null}
            {selectedCategory?.[0] === "Users" ? <Users /> : null}
            <Toaster position="bottom-center" reverseOrder={true} />
        </div>
    );
};

export default Form;
