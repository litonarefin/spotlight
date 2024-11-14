import { useCallback, useRef } from "@wordpress/element";

export const data = [
    {
        title: "Users",
        url: "",
        dependency: true
    },
    {
        title: "Logout",
        url: WPSPOTLIGHT_CORE.logout_url,
    },
];

const useDataStore = () => {
    let defaultData = {
        selectedCategory: [],
        searchText: "",
        result: [],
        defaultData: [...data] || [],
        resTime: 0,
        resultCount: 0,
        backspace: false,
        callback: null,
    };

    const store = useRef(defaultData);
    const get = useCallback(() => store.current, []);

    const subscribers = useRef(new Set());
    const set = useCallback((value) => {
        store.current = { ...store.current, ...value };
        return subscribers.current.forEach((callback) => callback());
    }, []);

    const subscribe = useCallback((callback) => {
        subscribers.current.add(callback);
        return () => subscribers.current.delete(callback);
    }, []);

    return { get, set, subscribe };
};

export default useDataStore;
