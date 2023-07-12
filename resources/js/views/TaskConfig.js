export default {
    data: () => {
        return {
            statuses: [
                {
                    id: null,
                    title: '',
                    tasks: [
                        {
                            id: null,
                            parent_task_id: null,
                            title: '',
                            description: '',
                            status_id: null,
                            priority: null,
                            created_at: '',
                            deadline: '',
                            tags: [
                                {
                                    id: null,
                                    title: '',
                                }
                            ],
                            sub_tasks: [
                                {
                                    id: null,
                                    parent_task_id: null,
                                    title: '',
                                    description: '',
                                    status_id: null,
                                    priority: null,
                                    created_at: '',
                                    deadline: '',
                                }
                            ],
                        }
                    ]
                }
            ],

        }

    },

    singleTask: () => {
        return {
            id: null,
            parent_task_id: null,
            title: '',
            description: '',
            status_id: null,
            status: {
                id: null,
                title: '',
            },
            priority: null,
            // created_at: '',
            deadline: '',
            tags: [],
            sub_tasks: [
                {
                    id: null,
                    parent_task_id: null,
                    title: '',
                    description: '',
                    status_id: null,
                    priority: null,
                    created_at: '',
                    deadline: '',
                    status: {
                        id: null,
                        title: '',
                    },
                    tags: [
                        {
                            id: null,
                            title: '',
                        }
                    ]
                }
            ],
        }
    },
}

