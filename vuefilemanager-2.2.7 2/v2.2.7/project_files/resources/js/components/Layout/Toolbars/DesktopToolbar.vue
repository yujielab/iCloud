<template>
    <div class="hidden lg:block">
        <div class="nav-apple">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-16">
                    <NavigationBar />

                    <div class="flex items-center space-x-4">
                        <!--Create button-->
                        <PopoverWrapper>
                            <button class="btn-apple btn-primary"
                                @click.stop="showCreateMenu"
                            >
                                <span class="mr-2">{{ $t('create_something') }}</span>
                                <i class="material-icons text-sm">add</i>
                            </button>
                            <PopoverItem name="desktop-create" side="left">
                                <div class="card-apple glass">
                                    <OptionGroup :title="$t('frequently_used')">
                                        <OptionUpload
                                            :title="$t('upload_files')"
                                            type="file"
                                            :class="{
                                                'opacity-50 cursor-not-allowed': (isSharedWithMe && !canEdit) || canUploadInView || isTeamFolderHomepage || isSharedWithMeHomepage,
                                            }"
                                        />
                                        <Option
                                            @click.native="$createFolder"
                                            :class="{
                                                'opacity-50 cursor-not-allowed': (isSharedWithMe && !canEdit) || canCreateFolder || isTeamFolderHomepage || isSharedWithMeHomepage,
                                            }"
                                            :title="$t('create_folder')"
                                            icon="folder-plus"
                                        />
                                    </OptionGroup>
                                    <OptionGroup :title="$t('others')">
                                        <OptionUpload
                                            :class="{
                                                'opacity-50 cursor-not-allowed': (isSharedWithMe && !canEdit) || canUploadFolderInView || isTeamFolderHomepage || isSharedWithMeHomepage,
                                            }"
                                            :title="$t('upload_folder')"
                                            type="folder"
                                        />
                                        <Option
                                            @click.stop.native="$openRemoteUploadPopup"
                                            :title="$t('remote_upload')"
                                            icon="remote-upload"
                                        />
                                        <Option
                                            @click.stop.native="$createTeamFolder"
                                            :class="{ 'opacity-50 cursor-not-allowed': canCreateTeamFolder }"
                                            :title="$t('create_team_folder')"
                                            icon="users"
                                        />
                                        <Option
                                            @click.native="$createFileRequest"
                                            :title="$t('create_file_request')"
                                            icon="upload-cloud"
                                        />
                                    </OptionGroup>
                                </div>
                            </PopoverItem>
                        </PopoverWrapper>

                        <!--Search bar-->
                        <SearchBarButton class="input-apple w-64" />

                        <!--File Controls-->
                        <div class="flex items-center space-x-2">
                            <!--Team Heads-->
                            <PopoverWrapper v-if="$isThisRoute($route, ['TeamFolders', 'SharedWithMe'])">
                                <TeamMembersButton
                                    @click.stop.native="showTeamFolderMenu"
                                    size="32"
                                    class="btn-apple btn-secondary"
                                />
                                <PopoverItem name="team-folder" side="left">
                                    <div class="card-apple glass">
                                        <TeamFolderPreview />
                                        <OptionGroup v-if="$isThisRoute($route, ['TeamFolders'])" :title="$t('options')">
                                            <Option
                                                @click.native="$updateTeamFolder(teamFolder)"
                                                :title="$t('edit_members')"
                                                icon="rename"
                                            />
                                            <Option
                                                @click.native="$dissolveTeamFolder(teamFolder)"
                                                :title="$t('dissolve_team')"
                                                icon="trash"
                                            />
                                        </OptionGroup>
                                    </div>
                                </PopoverItem>
                            </PopoverWrapper>

                            <!--Action buttons-->
                            <div v-if="!$isMobile()" class="flex items-center space-x-2">
                                <button v-if="canShowConvertToTeamFolder"
                                    @click="$convertAsTeamFolder(clipboard[0])"
                                    :class="[
                                        'btn-apple btn-secondary',
                                        { 'opacity-50 cursor-not-allowed': !canCreateTeamFolder }
                                    ]"
                                >
                                    {{ $t('convert_into_team_folder') }}
                                </button>
                                <button v-if="!$isThisRoute($route, ['SharedWithMe', 'Public'])"
                                    @click="$shareFileOrFolder(clipboard[0])"
                                    :class="[
                                        'btn-apple btn-secondary',
                                        { 'opacity-50 cursor-not-allowed': canShareInView }
                                    ]"
                                >
                                    {{ $t('share_item') }}
                                </button>
                                <button
                                    @click="$moveFileOrFolder(clipboard[0])"
                                    :class="[
                                        'btn-apple btn-secondary',
                                        { 'opacity-50 cursor-not-allowed': canMoveInView && !canEdit }
                                    ]"
                                >
                                    {{ $t('move') }}
                                </button>
                                <button
                                    @click="$deleteFileOrFolder(clipboard[0])"
                                    :class="[
                                        'btn-apple btn-secondary',
                                        { 'opacity-50 cursor-not-allowed': canDeleteInView && !canEdit }
                                    ]"
                                >
                                    {{ $t('delete') }}
                                </button>
                            </div>
                        </div>

                        <!--View Controls-->
                        <div class="flex items-center space-x-2">
                            <PopoverWrapper>
                                <button class="btn-apple btn-secondary"
                                    @click.stop="showSortingMenu"
                                >
                                    {{ $t('sorting_view') }}
                                </button>
                                <PopoverItem name="desktop-sorting" side="left">
                                    <div class="card-apple glass">
                                        <FileSortingOptions />
                                    </div>
                                </PopoverItem>
                            </PopoverWrapper>
                            <button class="btn-apple btn-secondary"
                                @click="$store.dispatch('fileInfoToggle')"
                            >
                                {{ $t('info_panel') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <UploadProgress class="mt-16" />
    </div>
</template>

<script>
import TeamMembersButton from '../../Teams/Components/TeamMembersButton'
import TeamFolderPreview from '../../Teams/Components/TeamFolderPreview'
import PopoverWrapper from '../../UI/Popover/PopoverWrapper'
import FileSortingOptions from '../../Menus/FileSortingOptions'
import PopoverItem from '../../UI/Popover/PopoverItem'
import UploadProgress from '../../UI/Others/UploadProgress'
import NavigationBar from './NavigationBar'
import ToolbarButton from '../../UI/Buttons/ToolbarButton'
import OptionUpload from '../../Menus/Components/OptionUpload'
import OptionGroup from '../../Menus/Components/OptionGroup'
import SearchBarButton from '../../UI/Buttons/SearchBarButton'
import { events } from '../../../bus'
import { mapGetters } from 'vuex'
import Option from '../../Menus/Components/Option'

export default {
    name: 'DesktopToolbar',
    components: {
        FileSortingOptions,
        TeamMembersButton,
        TeamFolderPreview,
		SearchBarButton,
        UploadProgress,
        PopoverWrapper,
        NavigationBar,
        ToolbarButton,
        OptionUpload,
        OptionGroup,
        PopoverItem,
        Option,
    },
    computed: {
        ...mapGetters([
            'isVisibleNavigationBars',
            'currentTeamFolder',
            'currentFolder',
            'sharedDetail',
            'clipboard',
            'user',
        ]),
        canEdit() {
            if (this.currentTeamFolder && this.user) {
                let member = this.currentTeamFolder.data.relationships.members.data.find(
                    (member) => member.data.id === this.user.data.id
                )

                return member.data.attributes.permission === 'can-edit'
            }

            return false
        },
        teamFolder() {
            return this.currentTeamFolder ? this.currentTeamFolder : this.clipboard[0]
        },
        isTeamFolderHomepage() {
            return this.$isThisRoute(this.$route, ['TeamFolders']) && !this.$route.params.id
        },
        isSharedWithMe() {
            return this.$isThisRoute(this.$route, ['SharedWithMe'])
        },
        isSharedWithMeHomepage() {
            return this.$isThisRoute(this.$route, ['SharedWithMe']) && !this.$route.params.id
        },
        canCreateFolder() {
            return !this.$isThisRoute(this.$route, ['Files', 'Public', 'TeamFolders', 'SharedWithMe'])
        },
        canShowConvertToTeamFolder() {
            return this.$isThisRoute(this.$route, ['Files', 'MySharedItems'])
        },
        canUploadInView() {
            return !this.$isThisRoute(this.$route, ['Files', 'RecentUploads', 'Public', 'TeamFolders', 'SharedWithMe'])
        },
        canUploadFolderInView() {
            return !this.$isThisRoute(this.$route, ['Files', 'Public', 'TeamFolders', 'SharedWithMe'])
        },
        canDeleteInView() {
            let routes = ['TeamFolders', 'SharedWithMe', 'RecentUploads', 'MySharedItems', 'Trash', 'Public', 'Files']
            return !this.$isThisRoute(this.$route, routes) || this.clipboard.length === 0
        },
        canMoveInView() {
            let routes = ['SharedWithMe', 'RecentUploads', 'MySharedItems', 'Public', 'Files', 'TeamFolders']
            return !this.$isThisRoute(this.$route, routes) || this.clipboard.length === 0
        },
        canShareInView() {
            let routes = ['TeamFolders', 'RecentUploads', 'MySharedItems', 'Public', 'Files']
            return !this.$isThisRoute(this.$route, routes) || this.clipboard.length > 1 || this.clipboard.length === 0
        },
        canCreateTeamFolder() {
            return (
                this.$isThisRoute(this.$route, ['MySharedItems', 'Files']) &&
                this.clipboard.length === 1 &&
                this.clipboard[0].data.type === 'folder'
            )
        },
    },
    methods: {
        showTeamFolderMenu() {
            if (this.teamFolder) events.$emit('popover:open', 'team-folder')
        },
        showCreateMenu() {
            events.$emit('popover:open', 'desktop-create')
        },
        showSortingMenu() {
            events.$emit('popover:open', 'desktop-sorting')
        },
    },
}
</script>
