import React, { useEffect } from "react";
import SearchList from "./SearchList";
import { useStore } from "../store/useStore";
import apiFetch from "@wordpress/api-fetch";
import { data } from "../store/useDataStore";

const Users = () => {
    const [fields, setStore] = useStore((store) => store);
    const { defaultData, searchText, selectedCategory, backspace } = fields;

    function getAllUserRole() {
        let startTime = new Date().getTime(),
            endTime;
        apiFetch({ path: "/wp-json/wpspotlight/v1/get-user-roles" }).then((roles) => {
            endTime = new Date().getTime();

            const userRoles = [];
            Object.values(roles).forEach((item) => {
                userRoles.push({
                    title: item,
                    url: "https://author.com",
                    dependency: true,
                });
            });

            setStore({
                defaultData: userRoles,
                result: [],
                resTime: endTime - startTime,
                resultCount: Object.values(roles).length,
            });
        });
    }

    function getUsersByRole(role) {
        let startTime = new Date().getTime(),
            endTime;
        apiFetch({ path: `/wp-json/wpspotlight/v1/get-role-by-users?role=${role}` }).then(
            (userData) => {
                endTime = new Date().getTime();

                const users = [];
                userData.forEach((user) => {
                    users.push({
                        title: `${user.name} (${user.email})`,
                        url: user.edit_url,
                        icon: "users",
                    });
                });

                setStore({
                    defaultData: users,
                    result: [],
                    resTime: endTime - startTime,
                    resultCount: users.length,
                });
            }
        );
    }

    function getUsersByEmailAndRole(email, role) {
        let startTime = new Date().getTime(),
            endTime;
        apiFetch({
            path: `/wp-json/wpspotlight/v1/get-users-by-email?email=${email}&role=${role}`,
        }).then((userData) => {
            endTime = new Date().getTime();

            const users = [];
            userData.forEach((user) => {
                users.push({
                    title: `${user.name} (${user.email})`,
                    url: user.edit_url,
                    icon: "users",
                });
            });

            setStore({
                defaultData: users,
                result: [],
                resTime: endTime - startTime,
                resultCount: users.length,
            });
        });
    }

    function getUsersByEmail(email) {
        let startTime = new Date().getTime(),
            endTime;
        apiFetch({
            path: `/wp-json/wpspotlight/v1/get-users-by-email?email=${email}`,
        }).then((userData) => {
            endTime = new Date().getTime();

            const users = [];
            userData.forEach((user) => {
                users.push({
                    title: `${user.roles?.[0]} > ${user.name} (${user.email})`,
                    url: user.edit_url,
                    icon: "users",
                });
            });

            setStore({
                defaultData: users,
                result: [],
                resTime: endTime - startTime,
                resultCount: users.length,
            });
        });
    }

    useEffect(() => {
        if (selectedCategory?.length > 0 && backspace) {
            selectedCategory.pop();
            setStore({ selectedCategory: selectedCategory, backspace: false });
        }

        if (selectedCategory?.[0] === "Users" && selectedCategory?.length === 1) {
            getAllUserRole();
        } else if (selectedCategory?.[0] === "Users" && selectedCategory?.length === 2) {
            getUsersByRole(selectedCategory?.[1]);
        } else if (selectedCategory?.length === 0) {
            setStore({ defaultData: data });
        }
    }, [selectedCategory, backspace]);

    useEffect(() => {
        if (selectedCategory?.[0] === "Users" && selectedCategory?.length === 2 && searchText) {
            getUsersByEmailAndRole(searchText, selectedCategory[1]);
        } else if (
            selectedCategory?.[0] === "Users" &&
            selectedCategory?.length === 1 &&
            searchText
        ) {
            getUsersByEmail(searchText);
        } else if (
            selectedCategory?.[0] === "Users" &&
            selectedCategory?.length === 1 &&
            !searchText
        ) {
            getAllUserRole();
        }
    }, [searchText]);

    return <SearchList />;
};

export default Users;
