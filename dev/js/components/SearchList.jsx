import React, { useEffect, useRef, useState } from "react";
import { useStore } from "../store/useStore";
import { getIcon } from "../utils/icons";
import Img from "./Img";
import { stringCount } from "../utils/stringCount";

const SearchList = () => {
    const [selectedItem, setSelectedItem] = useState(-1);

    const [fields, setStore] = useStore((store) => store);
    const { defaultData, resTime, resultCount, searchText, result, selectedCategory, callback } =
        fields;

    const inputRef = useRef();

    const handleKeyDown = (e) => {
        if (e.key === "ArrowUp" && selectedItem > 0) {
            setSelectedItem((prev) => prev - 1);
        } else if (e.key === "ArrowDown" && selectedItem < result?.length - 1) {
            setSelectedItem((prev) => prev + 1);
        } else if (
            e.key === "ArrowDown" &&
            selectedItem < defaultData?.length - 1 &&
            result?.length === 0
        ) {
            setSelectedItem((prev) => prev + 1);
        } else if (e.key === "Enter" && selectedItem >= 0) {
            if (result[selectedItem]?.item?.dependency) {
                setStore({
                    searchText: "",
                    selectedCategory: [...selectedCategory, result[selectedItem]?.item?.title],
                    result: [],
                    defaultData: result[selectedItem]?.item?.items || [],
                });
                if (result[selectedItem]?.item?.callback) {
                    callback(result[selectedItem]?.item);
                }
            } else if (result[selectedItem]?.item?.callback) {
                if (result[selectedItem]?.item?.dependency) {
                    setStore({
                        searchText: "",
                        selectedCategory: [...selectedCategory, result[selectedItem]?.item?.title],
                        result: [],
                        defaultData: result[selectedItem]?.item?.items || [],
                    });
                }
                callback(result[selectedItem]?.item);
            } else if (defaultData[selectedItem]?.dependency && !result?.length) {
                setStore({
                    searchText: "",
                    selectedCategory: [...selectedCategory, defaultData[selectedItem]?.title],
                    result: [],
                    defaultData: defaultData[selectedItem]?.items || [],
                });
                if (defaultData[selectedItem]?.callback) {
                    callback(defaultData[selectedItem]);
                }
            } else if (defaultData[selectedItem]?.callback && !result?.length) {
                if (defaultData[selectedItem]?.dependency) {
                    setStore({
                        searchText: "",
                        selectedCategory: [...selectedCategory, defaultData[selectedItem]?.title],
                        result: [],
                        defaultData: defaultData[selectedItem]?.items || [],
                    });
                }
                callback(defaultData[selectedItem]);
            } else {
                if (searchText) {
                    window.open(result[selectedItem]?.item?.url, "_self");
                } else {
                    window.open(defaultData[selectedItem]?.url, "_self");
                }
            }
        } else if (e.key == "Backspace" && !searchText && selectedCategory?.length > 0) {
            setStore({ backspace: true });
        }
    };

    useEffect(() => {
        let inputTimeout = setTimeout(() => {
            inputRef.current && inputRef.current.focus();
        }, 5);

        return () => clearTimeout(inputTimeout);
    }, []);

    return (
        <div className="search">
            <div className="search-header">
                <p>
                    {resultCount} results ({resTime}ms)
                </p>
                <div className="search-action">
                    <div className="jltpu_selected-category">
                        {selectedCategory?.map((item, i) => (
                            <button type="button" key={i}>
                                <span dangerouslySetInnerHTML={{ __html: item }} />
                                {getIcon("rightArrow")}
                            </button>
                        ))}
                    </div>

                    <input
                        ref={inputRef}
                        type="text"
                        placeholder="type a command..."
                        value={searchText}
                        onChange={(e) => setStore({ searchText: e.target.value })}
                        onKeyDown={handleKeyDown}
                    />
                </div>
            </div>

            <div className="search-body">
                <ul>
                    {result?.map((res, i) => (
                        <li key={i}>
                            <a
                                href={!res.item?.dependency ? res?.item?.url : "#"}
                                {...(res?.item?.newWindow ? { target: "_blank" } : {})}
                                onClick={(e) => {
                                    if (res?.item?.callback) {
                                        e.preventDefault();
                                        if (res?.item?.dependency) {
                                            setStore({
                                                searchText: "",
                                                selectedCategory: [
                                                    ...selectedCategory,
                                                    res?.item.title,
                                                ],
                                                result: [],
                                            });
                                        }
                                        return callback(res.item);
                                    }

                                    if (!res.item?.dependency) return;
                                    e.preventDefault();

                                    setStore({
                                        searchText: "",
                                        selectedCategory: [...selectedCategory, res?.item.title],
                                        result: [],
                                    });
                                }}
                                className={selectedItem === i ? `jltpu_active` : ""}>
                                <div className="jltpu_title-wrapper">
                                    {res.item?.img ? (
                                        <Img src={res.item.img} alt={res.item.title} />
                                    ) : null}

                                    {getIcon(res.item.icon)}
                                    <span
                                        dangerouslySetInnerHTML={{
                                            __html: stringCount(res.item.title, 50),
                                        }}
                                    />
                                </div>
                                {res?.item?.tags ? (
                                    <div className="jltpu_tags">
                                        {res.item.tags.map((tag, i) => (
                                            <span
                                                key={i}
                                                {...(tag.bg
                                                    ? { className: "jltpu_tag-badge" }
                                                    : {})}>
                                                {tag.name}
                                            </span>
                                        ))}
                                    </div>
                                ) : null}
                            </a>
                        </li>
                    ))}

                    {!result?.length &&
                        defaultData?.map((res, i) => (
                            <li key={i}>
                                <a
                                    href={!res?.dependency ? res?.url : "#"}
                                    {...(res?.newWindow ? { target: "_blank" } : {})}
                                    onClick={(e) => {
                                        if (res?.callback) {
                                            e.preventDefault();
                                            if (res?.dependency) {
                                                setStore({
                                                    searchText: "",
                                                    selectedCategory: [
                                                        ...selectedCategory,
                                                        res.title,
                                                    ],
                                                    result: [],
                                                });
                                            }
                                            return callback(res);
                                        }

                                        if (!res.dependency) return;
                                        e.preventDefault();

                                        setStore({
                                            searchText: "",
                                            selectedCategory: [...selectedCategory, res.title],
                                            result: [],
                                        });
                                    }}
                                    className={selectedItem === i ? `jltpu_active` : ""}>
                                    <div className="jltpu_title-wrapper">
                                        {res?.img ? <Img src={res.img} alt={res.title} /> : null}

                                        {getIcon(res.icon)}
                                        <span
                                            dangerouslySetInnerHTML={{
                                                __html: stringCount(res.title, 50),
                                            }}
                                        />
                                    </div>

                                    {res.tags ? (
                                        <div className="jltpu_tags">
                                            {res.tags.map((tag, i) => (
                                                <span
                                                    key={i}
                                                    {...(tag.bg
                                                        ? { className: "jltpu_tag-badge" }
                                                        : {})}>
                                                    {tag.name}
                                                </span>
                                            ))}
                                        </div>
                                    ) : null}
                                </a>
                            </li>
                        ))}
                </ul>
            </div>
        </div>
    );
};

export default SearchList;
